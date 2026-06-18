<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import TimKerjaForm from '~/components/admin/TimKerjaForm.vue'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],

  components: {
    TimKerjaForm,
  },

  async asyncData({ params }) {
    const { data } = await axios.get(`tim-kerja/${params.id}`)

    return {
      form: {
        id: data.id,
        nama: data.nama,
        satuan_kerja_id: data.satuan_kerja_id,
        v_struktur_organisasi_id: data.v_struktur_organisasi_id,
        nip_ketua: data.nip_ketua,
        ketua: data.ketua,
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

        await axios.patch(`tim-kerja/${this.form.id}`, this.form)

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.$router.push('/admin/tim-kerja')
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
  <TimKerjaForm :form="form" :is-busy="isBusy" mode="edit" @submit="update" />
</template>
