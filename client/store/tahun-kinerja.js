import Cookies from 'js-cookie'

export const state = () => ({
  tahunKinerja: null,
  tahunKinerjaPublic: null,
})

export const getters = {
  tahunKinerja: state => state.tahunKinerja,
  tahunKinerjaPublic: state => state.tahunKinerjaPublic,
}

export const mutations = {
  SET_TAHUN_KINERJA (state, tahunKinerja) {
    state.tahunKinerja = tahunKinerja
  },
  REMOVE_TAHUN_KINERJA (state) {
    state.tahunKinerja = null
  },
  SET_TAHUN_KINERJA_PUBLIC (state, tahunKinerja) {
    state.tahunKinerjaPublic = tahunKinerja
  },
}

export const actions = {
  setTahunKinerja ({ commit }, tahunKinerja) {
    commit('SET_TAHUN_KINERJA', tahunKinerja)

    Cookies.set(process.env.tahunKinerjaKey, tahunKinerja)
  },
  removeTahunKinerja ({ commit }) {
    commit('REMOVE_TAHUN_KINERJA')
    Cookies.remove(process.env.tahunKinerjaKey)
  },
  setTahunKinerjaPublic ({ commit }, tahunKinerja) {
    commit('SET_TAHUN_KINERJA_PUBLIC', tahunKinerja)

    Cookies.set(process.env.tahunKinerjaPublicKey, tahunKinerja)
  },
}