<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import ProgramForm from '~/components/admin/ProgramForm.vue'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],

  components: {
    ProgramForm,
  },

  async asyncData({ params }) {
    const { data } = await axios.get(`program-data/${params.id}`)

    return {
      form: {
        id: data.id,
        kode: data.kode,
        nama: data.nama,
        satuan_kerja_id: data.satuan_kerja_id,
        tahun_kinerja: data.tahun_kinerja,
        anggaran: data.anggaran || 0,
      },
    }
  },

  data() {
    return {
      isBusy: false,
    }
  },

  methods: {
    async update() {
      try {
        this.isBusy = true

        await axios.patch(`program-data/${this.form.id}`, this.form)

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
  <ProgramForm :form="form" :is-busy="isBusy" @submit="update" />
</template>
