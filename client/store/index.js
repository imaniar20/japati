import Cookies from 'js-cookie'
import { cookieFromRequest } from '~/utils'

export const actions = {
  nuxtServerInit ({ commit }, { req }) {
    const token = cookieFromRequest(req, 'token')
    if (token) {
      commit('auth/SET_TOKEN', token)
    }
  },

  nuxtClientInit ({ commit }) {
    var queryDict = {}
    var token
    var remember = false
    window.location.search.substr(1).split("&").forEach(function(item) {queryDict[item.split("=")[0]] = item.split("=")[1]})
    if (queryDict.token) {
      token = queryDict.token
      if (token) {
        Cookies.set('esakip_token', token, { expires: remember ? 365 : null })
      }
    } else {
      token = Cookies.get('esakip_token')
      if (!token || token == 'undefined') {
        token = null
      }
    }
    if (queryDict.message) {
      alert(queryDict.message)
    }

    if (token && token != 'undefined') {
      commit('auth/SET_TOKEN', token)
    }

    const tahunKinerja = Cookies.get(process.env.tahunKinerjaKey)
    if (tahunKinerja) {
      commit('tahun-kinerja/SET_TAHUN_KINERJA', tahunKinerja)
    }
  }
}
