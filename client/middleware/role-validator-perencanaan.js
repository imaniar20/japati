export default ({ store, redirect, $role }) => {
  if (!store.getters['auth/check']) {
    return redirect('/login')
  }

  if (!$role.isValidatorPerencanaan1() && !$role.isValidatorPerencanaan2() && !$role.isValidatorPerencanaan3()) {
    return $nuxt.error({ statusCode: 403, message: 'Anda tidak memiliki hak akses ke halaman ini' })
  }
}
