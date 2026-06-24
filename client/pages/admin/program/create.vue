<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import ProgramForm from '~/components/admin/ProgramForm.vue'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],

  components: {
    ProgramForm,
  },

  data() {
    return {
      form: {
        kode: null,
        nama: null,
        satuan_kerja_id: null,
        tahun_kinerja: this.$helper.getTahunKinerja(),
        anggaran: 0,
      },
      isBusy: false,
    }
  },

  created() {
    if (!this.$role.isSuper()) {
      this.form.satuan_kerja_id = this.$store.getters['auth/user'].satuan_kerja_id
    }
  },

  methods: {
    async store() {
      try {
        this.isBusy = true

        await axios.post('program-data', this.form)

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data',
        })

        this.$router.push('/admin/program')
      } catch (error) {
        Swal.fire({
          type: 'error',
          title: error.response?.data?.message || 'Gagal simpan data!',
        })
      } finally {
        this.isBusy = false
      }
    },
  },
}
</script>

<template>
  <ProgramForm :form="form" :is-busy="isBusy" @submit="store" />
</template>
