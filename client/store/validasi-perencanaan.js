import axios from 'axios'

export const state = () => ({
  data: null,
})

function canEdit(state) {
  return !state.data || state.data.status === false // belum submit atau sudah submit tapi statusnya ditolak (false)
}

function canEditTahap(state, tahap) {
  return !state.data
    || (canEdit(state) && state.data.tahap == tahap && state.data.status === false)
}

export const getters = {
  data: state => state.data,
  canEdit: state => canEdit(state),
  canEditTahap1: state => canEditTahap(state, 1),
  canEditTahap2: state => canEditTahap(state, 2),
  canEditTahap3: state => canEditTahap(state, 3),
}

export const mutations = {
  SET_data (state, data) {
    state.data = data
  },
}

export const actions = {
  async getStatus({ commit }) {
    try {
      const { data } = await axios.get('validasi-perencanaan')
      
      commit('SET_data', data.data)
    } catch (error) {
      console.log(error);
    }
  },
}