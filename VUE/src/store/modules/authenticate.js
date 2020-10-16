import axios from 'axios';

const state = {
  status: '',
  token: localStorage.getItem('token') || '',
  user : JSON.parse(localStorage.getItem('user')) || {}
};

const getters = {
  isLoggedIn: state => !!state.token,
  authStatus: state => state.status,
  authUser: state => state.user
};

const actions = {
  login({commit}, credential){
    return new Promise((resolve, reject) => {

      commit('auth_request')

      axios({url: 'http://localhost:8000/api/auth/login', data: credential, method: 'POST' })
      .then(resp => {
        const token = resp.data.data.token
        const user = resp.data.data.user

        localStorage.setItem('token', token)
        localStorage.setItem('user', JSON.stringify(user))

        axios.defaults.headers.common['Authorization'] = "Bearer " + token

        commit('auth_success', {token, user})
        resolve(resp)

      })
      .catch(err => {
        commit('auth_error')        
        localStorage.removeItem('token')
        reject(err)
      })

    })
  },

  logout({commit}){
    return new Promise((resolve, reject) => {

      axios({url: 'http://localhost:8000/api/auth/logout', data: {}, method: 'POST' })
      .then(resp => {     
        // Removing token from local storage and Vuex.
        commit('logout')
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        delete axios.defaults.headers.common['Authorization']

        resolve(resp)
      })
      .catch(err => {
        reject(err)
      })

    })
  },

  reset({commit}) {
    return new Promise((resolve) => {
        commit('logout')
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        delete axios.defaults.headers.common['Authorization']

        resolve()

    })
  }
};

const mutations = {
  auth_request(state){
    state.status = 'loading'
  },

  auth_success(state, data){
    state.status = 'success'
    state.token = data.token
    state.user = data.user
  },

  auth_error(state){
    state.status = 'failed'
  },

  logout(state){
    state.status = ''
    state.token = ''
  },
};

export default {
  state,
  getters,
  actions,
  mutations
};