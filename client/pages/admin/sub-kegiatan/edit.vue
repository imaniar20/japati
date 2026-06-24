<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import SubKegiatanForm from '~/components/admin/SubKegiatanForm.vue'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],

  components: {
    SubKegiatanForm,
  },

  async asyncData({ params }) {
    const { data } = await axios.get(`sub-kegiatan-data/${params.id}`)

    return {
      form: {
        id: data.id,
        kode: data.kode,
        nama: data.nama,
        kegiatan_id: data.kegiatan_id,
        satuan_kerja_id: data.satuan_kerja_id,
        tahun_kinerja: data.tahun_kinerja,
        anggaran: data.anggaran || 0,
        indikator: data.indikator || [],
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

        await axios.patch(`sub-kegiatan-data/${this.form.id}`, this.form)

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
  <SubKegiatanForm :form="form" :is-busy="isBusy" @submit="update" />
</template>
