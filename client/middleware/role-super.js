export default ({ store, redirect, $role }) => {
  if (!store.getters['auth/check']) {
    return redirect('/login')
  }

  if (store.getters['auth/user'].role_id !== $role.super) {
    return $nuxt.error({ statusCode: 403, message: 'Anda tidak memiliki hak akses ke halaman ini' })
  }
}
