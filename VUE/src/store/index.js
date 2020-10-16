import Vue from "vue";
import Vuex from "vuex";

import authentication from './modules/authenticate';
import users from './modules/users'

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    authentication,
    users
  }
});
