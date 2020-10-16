<template>
  <div id="app" :class="{'login-page': getCurrentRoute() == 'Login'}">

    	<Preloader v-if="false"/>

      <Header v-show="getCurrentRoute() != 'Login'"/>

      <RightSidebar v-show="getCurrentRoute() != 'Login'"/>

      <LeftSidebar v-show="getCurrentRoute() != 'Login'"/>

      <div v-show="getCurrentRoute() != 'Login'" class="mobile-menu-overlay"></div>

      <div v-if="getCurrentRoute() != 'Login'" class="main-container">
        <div class="pd-ltr-20">          
          <router-view/>          
          <Footer v-if="false"/>
        </div>
      </div>
      <div v-else>
        <router-view/>
      </div>

  </div>
</template>

<script>
import Header from '@/components/Header.vue'
import LeftSidebar from '@/components/LeftSidebar.vue'
import RightSidebar from '@/components/RightSidebar.vue'
import Footer from '@/components/Footer.vue'
import Preloader from '@/components/Preloader'

import { mapGetters, mapActions } from 'vuex'

export default {
  components: {
    Header,
    LeftSidebar,
    RightSidebar,
    Footer,
    Preloader
  },

  computed: mapGetters(['authUser']),

  created: function () {
    this.interceptAxios401()
  },

  methods: {
    ...mapActions(['reset']),

    getCurrentRoute() {
      return this.$route.name;
    },


    interceptAxios401() {
      let store = this.$store
      let router = this.$router

      this.$axios.interceptors.response.use(
        function (response) {
          return response;
        },
        function (error) {
          if (401 === error.response.status) {
            store.dispatch('reset')
            router.push('/login')
          } else {
            return Promise.reject(error);
          }
        }
      );
    }
  },
}
</script>

<style>

</style>
