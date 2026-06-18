<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import PegawaiForm from '~/components/admin/PegawaiForm.vue'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],

  components: {
    PegawaiForm,
  },

  data() {
    return {
      form: {
        peg_nip: null,
        id_satuan_kerja: null,
        peg_nama: null,
        jabatan_nama: null,
        unit_kerja_nama: null,
        peg_status: '1',
      },
      isBusy: false,
    }
  },

  created() {
    if (!this.$role.isSuper()) {
      this.form.id_satuan_kerja = this.$store.getters['auth/user'].satuan_kerja_id
    }
  },

  methods: {
    async store() {
      try {
        this.isBusy = true

        await axios.post('pegawai-data', this.form)

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.$router.push('/admin/pegawai')
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
  <PegawaiForm :form="form" :is-busy="isBusy" mode="create" @submit="store" />
</template>
