const state = {
  debts: null,
  form: {}
}

const mutations = {
  fillDebts(state, debts) {
    state.debts = debts
  }
}

const actions = {
  index: function({ commit, dispatch }, params) {
    return new Promise((resolve, reject) => {
      axios
        .get('/api/debts', { params: params })
        .then(response => {
          console.log(response.data)
          commit('fillDebts', response.data)
          resolve(response.data)
        })
        .catch(error => {
          dispatch('errors/errorHandle', error.response, {
            root: true
          })
          reject(error.response)
        })
    })
  },

  pay({ dispatch }, params) {
    console.log(params)
    return new Promise((resolve, reject) => {
      axios
        .post('/api/debts/confirm', params)
        .then(response => {
          resolve(response.data)
        })
        .catch(error => {
          dispatch('errors/errorHandle', error.response, {
            root: true
          })
          reject(error.response)
        })
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
