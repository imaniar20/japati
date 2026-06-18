<script>
import axios from 'axios'
import Swal from 'sweetalert2'
import TimKerjaForm from '~/components/admin/TimKerjaForm.vue'

export default {
  middleware: ['auth', 'role-perangkat-daerah'],

  components: {
    TimKerjaForm,
  },

  data() {
    return {
      form: {
        nama: null,
        satuan_kerja_id: null,
        v_struktur_organisasi_id: null,
        nip_ketua: null,
        ketua: null,
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

        await axios.post('tim-kerja', this.form)

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
  <TimKerjaForm :form="form" :is-busy="isBusy" mode="create" @submit="store" />
</template>
