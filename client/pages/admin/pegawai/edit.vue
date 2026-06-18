<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import PegawaiForm from '~/components/admin/PegawaiForm.vue'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],

  components: {
    PegawaiForm,
  },

  async asyncData({ params }) {
    const { data } = await axios.get(`pegawai-data/${params.id}`)

    return {
      form: {
        id: data.id,
        peg_nip: data.peg_nip,
        id_satuan_kerja: data.id_satuan_kerja,
        peg_nama: data.peg_nama,
        jabatan_nama: data.jabatan_nama,
        unit_kerja_nama: data.unit_kerja_nama,
        peg_status: String(data.peg_status ?? '1'),
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

        await axios.patch(`pegawai-data/${this.form.id}`, this.form)

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
  <PegawaiForm :form="form" :is-busy="isBusy" mode="edit" @submit="update" />
</template>
