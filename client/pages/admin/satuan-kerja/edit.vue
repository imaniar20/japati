<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import SatuanKerjaForm from '~/components/admin/SatuanKerjaForm.vue'
import { createSatuanKerjaForm } from '~/utils/satuanKerjaForm'

export default {
  middleware: ['auth', 'role-super'],

  components: {
    SatuanKerjaForm,
  },

  async asyncData({ params }) {
    const { data } = await axios.get(`admin/satuan-kerja/${params.id}`)

    return {
      form: {
        ...createSatuanKerjaForm(data.tahun_id),
        ...data,
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

        await axios.patch(`admin/satuan-kerja/${this.form.satuan_kerja_id}`, this.form)

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.$router.push('/admin/satuan-kerja')
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
  <SatuanKerjaForm :form="form" :is-busy="isBusy" mode="edit" @submit="update" />
</template>
