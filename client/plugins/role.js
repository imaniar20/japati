  import authMiddleware from '../middleware/auth'

export default ({ app, $const }, inject) => {
  const superRole = 1
  const pemerintah_daerah = 2
  const perangkat_daerah = 3
  const setda = 4
  const pemda = 6
  const validator_bappeda = 7
  const validator_lke = 8
  const validator_lke_pleno = 9
  const validator_perencanaan_1 = 10
  const validator_perencanaan_2 = 11
  const validator_perencanaan_3 = 12
  const view_rapor_kinerja = 13;
  const view_all = 14;
  const validator_pengampu = 15;

  inject('role', {
    super: superRole,
    pemerintah_daerah,
    perangkat_daerah,
    setda,
    pemda,
    validator_bappeda,
    validator_lke,
    validator_lke_pleno,
    validator_perencanaan_1,
    validator_perencanaan_2,
    validator_perencanaan_3,
    view_all,
    validator_pengampu,
    
    isSuper: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === superRole
    },
    isPemerintahDaerah: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === pemerintah_daerah
    },
    isPerangkatDaerah: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === perangkat_daerah
    },
    isBiro: () => {
      return app.store.state.auth.user 
        && app.store.state.auth.user.satuan_kerja_id
        && app.store.state.auth.user.satuan_kerja_id != $const.SATKER_SETDA 
        && app.store.state.auth.user.satuan_kerja_id.toString().substr(0, 4) == $const.SATKER_SETDA
    },
    isSetda: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === setda
    },
    isGuest: () => {
      return app.store.state.auth.user == null
    },
    isPemda: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === pemda
    },
    isValidatorBappeda: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === validator_bappeda
    },
    isValidatorLKE: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === validator_lke
    },
    isValidatorPleno: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === validator_lke_pleno
    },
    isValidatorPerencanaan1: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === validator_perencanaan_1
    },
    isValidatorPerencanaan2: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === validator_perencanaan_2
    },
    isValidatorPerencanaan3: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === validator_perencanaan_3
    },
    isviewRaporKinerja: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === view_rapor_kinerja
    },
    isViewAll: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === view_all
    },
    isValidatorPengampu: () => {
      return app.store.state.auth.user && app.store.state.auth.user.role_id === validator_pengampu
    },
    /**
     * Cek apakah memiliki role yang sesuai,
     * ekspresi bisa menggunakan || atau &&.
     * Fungsi ini dibuat untuk kebutuhan middleware role yang lebih fleksibel
     * 
     * @param {string} expression logika ekspresi role
     * @param {boolean} throwError 
     */
    hasRoles: function (expression, throwError = true) {
      const super_admin = this.isSuper()
      const setda = this.isSetda()
      const pemerintah_daerah = this.isPemerintahDaerah()
      const perangkat_daerah = this.isPerangkatDaerah()
      const hasAuthMiddleware = expression.includes('auth')

      if (hasAuthMiddleware) {
        if (throwError) {
          authMiddleware($nuxt.context)
        }

        // ubah string `auth` jadi boolean dengan cek status login dari store
        expression = expression.replace('auth', app.store.getters['auth/check'] ? true : false)
      }

      const result = eval(expression)
      
      if (!throwError) return result

      if (!result) return $nuxt.error({ statusCode: 403, message: 'Anda tidak memiliki hak akses ke halaman ini' })
    },
  })
}