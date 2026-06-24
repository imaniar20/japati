<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import KegiatanForm from '~/components/admin/KegiatanForm.vue'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],

  components: {
    KegiatanForm,
  },

  async asyncData({ params }) {
    const { data } = await axios.get(`kegiatan-data/${params.id}`)

    return {
      form: {
        id: data.id,
        kode: data.kode,
        nama: data.nama,
        program_id: data.program_id,
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

        await axios.patch(`kegiatan-data/${this.form.id}`, this.form)

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data',
        })

        this.$router.push('/admin/kegiatan')
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
  <KegiatanForm :form="form" :is-busy="isBusy" @submit="update" />
</template>
