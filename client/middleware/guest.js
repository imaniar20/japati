export default function ({ store, redirect }) {
  if (store.state.auth.token && store.state.auth.user) {
    return redirect('/dashboard')
  }
}