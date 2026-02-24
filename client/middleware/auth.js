export default ({ store, redirect, route }) => {
  if (!store.getters['auth/check']) {
    localStorage.setItem('sakip-redirect-previous-url', route.path)
    return redirect('/login')
  }
}
