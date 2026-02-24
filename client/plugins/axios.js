import axios from 'axios'
import { BToast } from 'bootstrap-vue'
import Swal from 'sweetalert2'

process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0'

export default ({ app, store, redirect, $helper, error: nuxtError }) => {
  axios.defaults.baseURL = process.env.apiUrl

  if (process.server) {
    return
  }

  // Request interceptor
  axios.interceptors.request.use((request) => {
    request.baseURL = process.env.apiUrl

    const token = store.getters['auth/token']

    if (token) {
      request.headers.common.Authorization = `Bearer ${token}`
    }

    /**
     * header "process.env.tahunKinerjaKey" akan di terima oleh middleware app\Http\Middleware\SetTahunKinerja.php
     * header "process.env.tahunKinerjaPublicKey" akan di terima oleh middleware app\Http\Middleware\SetTahunKinerjaPublic.php
     */
     request.headers.common[process.env.tahunKinerjaKey] = $helper.getTahunKinerja()
     request.headers.common[process.env.tahunKinerjaPublicKey] = $helper.getTahunKinerjaPublic()

    return request
  })

  // Response interceptor
  axios.interceptors.response.use(response => response, (error) => {
    const { status } = error.response || {}
    const bsToaster = new BToast();
    let message = null

    try {
      message = error.response.data.message
    } catch (error) {
      
    }

    if (status >= 500) {
      Swal.fire({
        title: 'Error!',
        type: 'error',
        html: `Terjadi kesalahan saat memuat data <br><br> Kode error: ${status}`
      })
    } else if (status == 422) {
      bsToaster.$bvToast.toast('Terjadi kesalahan saat memuat data. Data tidak sesuai. Kode error 422', {
        title: 'Oops...',
        variant: 'danger',
        autoHideDelay: 5000,
        appendToast: true
      })
    } else if (status == 403 && message) {
      return Promise.reject(nuxtError({ statusCode: 403, message }))
    }

    if (status === 401 && store.getters['auth/check']) {
      Swal.fire({
        type: 'warning',
        title: 'Session Expired!',
        text: 'Please log in again to continue',
        reverseButtons: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel'
      }).then(() => {
        store.commit('auth/LOGOUT')

        redirect({ name: 'login' })
      })
    }

    return Promise.reject(error)
  })
}
