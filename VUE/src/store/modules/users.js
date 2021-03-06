import axios from 'axios';

const state = {
  users: {
    data: []
  }
};

const getters = {
  allUsers: state => state.users
};

const actions = {
  fetchUsers({commit}, [filter, url=null]) {

    return new Promise((resolve, reject) => {

      url = url ? url : 'http://localhost:8000/api/users'

      axios.get(url, { params: filter })

      .then(resp => {
        let usersData = resp.data.data
        commit('setUsers', usersData)
        resolve(resp)

      })
      .catch(err => {
        reject(err)
      })

    })
  },

  updateUserIfExists({commit}, user) {
    commit('updateUserIfExists', user)
  }

};

const mutations = {
  setUsers(state, data) {
    state.users = data
  },

  updateUserIfExists(state, user) {
    let outdatedUser = state.users.data.find(x => x.id == user.id);

    if (outdatedUser != undefined) {
      Object.assign(outdatedUser, user);
    }
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};