import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";

Vue.config.productionTip = false;

// Setting up axios.
import Axios from 'axios'
Vue.prototype.$axios = Axios;


// Setting up token in headers if available.
const token = localStorage.getItem('token')
if (token) {
  Vue.prototype.$axios.defaults.headers.common['Authorization'] = "Bearer " + token
}

// Setting up SweetAlert 2
import Swal from 'sweetalert2'
Vue.prototype.$Swal = Swal
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
Vue.prototype.$Toast = Toast


// import moment js
import moment from 'moment'

// Filters
Vue.filter('formatDate', function (value) {
  if (!value) return ''
  return moment(value).format('MMM Do YYYY, h:mm a');
})

// Setting up pusher
import Pusher from 'pusher-js';

let pusher = new Pusher('98c95e78886458a291c4', {
  cluster: 'ap1',
  authEndpoint: 'http://localhost:8000/broadcasting/auth',
  auth: {
    headers: { 
      Authorization: 'Bearer ' + token,
      Accept: 'application/json'
    }
  }  
});

Vue.prototype.$channel = pusher.subscribe(`private-users`);

Vue.prototype.$channel.pusher.connection.bind('connected', function() {
  let socketId = Vue.prototype.$channel.pusher.connection.socket_id
  Vue.prototype.$axios.defaults.headers.common['X-Socket-ID'] = socketId
});

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount("#app");
