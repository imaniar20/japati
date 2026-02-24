export default ({ store, redirect, $role }) => {
  if (!store.getters['auth/check']) {
    return redirect('/login')
  }

  const roleId = store.getters['auth/user'].role_id

  if (roleId !== $role.perangkat_daerah && roleId !== $role.super) {
    return $nuxt.error({ statusCode: 403, message: 'Anda tidak memiliki hak akses ke halaman ini' })
  }
}
