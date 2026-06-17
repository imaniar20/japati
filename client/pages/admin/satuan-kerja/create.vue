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

  data() {
    return {
      form: createSatuanKerjaForm(this.$helper.getTahunKinerja()),
      isBusy: false,
    }
  },

  methods: {
    async store() {
      try {
        this.isBusy = true

        await axios.post('admin/satuan-kerja', this.form)

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
  <SatuanKerjaForm :form="form" :is-busy="isBusy" mode="create" @submit="store" />
</template>
