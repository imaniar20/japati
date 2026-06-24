<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import SubKegiatanForm from '~/components/admin/SubKegiatanForm.vue'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],

  components: {
    SubKegiatanForm,
  },

  data() {
    return {
      form: {
        kode: null,
        nama: null,
        kegiatan_id: null,
        satuan_kerja_id: null,
        tahun_kinerja: this.$helper.getTahunKinerja(),
        anggaran: 0,
        indikator: [],
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

        await axios.post('sub-kegiatan-data', this.form)

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data',
        })

        this.$router.push('/admin/sub-kegiatan')
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
  <SubKegiatanForm :form="form" :is-busy="isBusy" @submit="store" />
</template>
