<script>
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  middleware: ['role-super'],

  data() {
    return {
      form: {
        tahun_kinerja: this.$helper.getTahunKinerja(),
        nilai: 0,
        efisiensi: 0,
      },
      isBusy: false,
    }
  },

  methods: {
    async store() {
      try {
        this.isBusy = true

        await axios.post('nilai-sakip-pemda', this.form)

        Swal.fire({
          type: 'success',
          title: 'Berhasil simpan data'
        })

        this.$router.push('/nilai-sakip-pemda')
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
  <b-card>
    <form @submit.prevent="store()">
      <div>
        <b-form-group label-cols="12" label-cols-md="2" label="Tahun" label-class="font-weight-bold" label-for="tahun_kinerja">
          <b-form-input type="number" step="1" id="tahun_kinerja" v-model="form.tahun_kinerja" min="0" required></b-form-input>
        </b-form-group>

        <b-form-group label-cols="12" label-cols-md="2" label="Nilai" label-class="font-weight-bold" label-for="nilai">
          <b-form-input type="number" step="0.001" id="nilai" v-model="form.nilai" min="0" required></b-form-input>
        </b-form-group>

        <b-form-group label-cols="12" label-cols-md="2" label="Efisiensi" label-class="font-weight-bold" label-for="efisiensi">
          <b-form-input type="number" step="0.001" id="efisiensi" v-model="form.efisiensi" min="0" required></b-form-input>
        </b-form-group>

        <div class="text-right mt-5">
          <b-button variant="primary" :disabled="isBusy" type="submit">
            <i v-if="isBusy" class="fa fa-spinner fa-pulse" aria-hidden="true"></i>
            <i v-else class="fa fa-floppy-o" aria-hidden="true"></i>
            Simpan
          </b-button>
        </div>
      </div>
    </form>
  </b-card>
</template>
