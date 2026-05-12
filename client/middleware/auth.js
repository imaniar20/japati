export default async function ({ store, redirect }) {
  const token = store.state.auth.token
  const user = store.state.auth.user

  // belum login
  if (!token) {
    return redirect('/login')
  }

  // token ada tapi user belum ke-load
  if (token && !user) {
    try {
      await store.dispatch('auth/fetchUser')
    } catch (e) {
      return redirect('/login')
    }
  }
}