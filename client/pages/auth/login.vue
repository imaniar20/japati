<script> 
import axios from 'axios'

export default {
  layout: 'guest',

  middleware: ['guest'],

  head () {
    return { title: 'Log In' }
  },

  data() {
    const tahunKinerja = this.$helper.getTahunKinerja()

    return {
      form: {
        username: null,
        password: null,
      },
      remember: false,
      tahunKinerja,
      isBusy: false,
      error: null,
    }
  },

  mounted() {
    // reset user data
    this.$store.dispatch('auth/updateUser', { user: null })
  },

  methods: {
    async login () {
      this.error = null
      this.isBusy = true

      // save tahun kinerja
      this.$helper.setTahunKinerja(this.tahunKinerja)
      this.$helper.setTahunKinerjaPublic(this.tahunKinerja)
      
      try {
        const { data: { data } } = await await axios.post('http://localhost:8000/login', this.form)

        // Save the token.
        this.$store.dispatch('auth/saveToken', {
          token: data.token.access_token,
          remember: this.remember
        })

        // Fetch the user.
        await this.$store.dispatch('auth/fetchUser')
        await this.$store.dispatch('validasi-perencanaan/getStatus')

        // check then remove prev redirect url
        const prevUrl = localStorage.getItem('sakip-redirect-previous-url')
        localStorage.removeItem('sakip-redirect-previous-url')

        if (this.$role.isValidatorBappeda()) {
          this.$router.push('/kamus-indikator-validasi-bappeda')
        } else if (this.$role.isValidatorLKE()) {
          this.$router.push('/lke/penilaian')
        } else if (prevUrl) {
          this.$router.push(prevUrl)
        } else if (this.$role.isValidatorPleno()) {
          this.$router.push('/lke/hasil-penilaian')
        } else if (this.$role.isValidatorPengampu()) {
          this.$router.push('/sasaran-strategis-pd/validasi-pengampu')
        } else {
          this.$router.push('/dashboard')
        }
      } catch (error) {
        this.error = error.response?.data?.message || 'Terjadi kesalahan'
      }

      this.isBusy = false
    }
  }
}
</script>

<template>
  <div class="container mt-5" style="max-width: 890px">
    <b-card header="Log In">
      <form @submit.prevent="login">
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Username</label>
          <div class="col-md-7">
            <input v-model="form.username" type="text" name="username" class="form-control" required>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Password</label>
          <div class="col-md-7">
            <input v-model="form.password" type="password" name="password" class="form-control" required>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Tahun Kinerja</label>
          <div class="col-md-7">
            <b-form-select v-model="tahunKinerja">
              <b-form-select-option 
                v-for="tahun of $const.tahun_kinerja_list" 
                :key="tahun.key" 
                :value="tahun.key"
              >
                {{ tahun.display }}
              </b-form-select-option>
            </b-form-select>
          </div>
        </div>

        <!-- alert -->
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right"></label>
          <div class="col-md-7">
            <b-alert variant="danger" :show="error != null">{{ error }}</b-alert>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-md-7 offset-md-3 d-flex">
            <b-button variant="primary" type="submit" :disabled="isBusy" class="w-100">
              <b-spinner small v-if="isBusy"></b-spinner>
              Login
            </b-button>
          </div>
        </div>
      </form>
    </b-card>
  </div>
</template>
