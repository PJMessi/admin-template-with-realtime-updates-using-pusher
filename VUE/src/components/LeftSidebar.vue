<template>
  <div class="leftsidebar">
    <div class="left-side-bar">
      <div class="brand-logo">
        <a href="index.html">
          <img src="vendors/images/deskapp-logo.svg" alt="" class="dark-logo">
          <img src="vendors/images/deskapp-logo-white.svg" alt="" class="light-logo">
        </a>
        <div class="close-sidebar" data-toggle="left-sidebar-close">
          <i class="ion-close-round"></i>
        </div>
      </div>
      <div class="menu-block customscroll">
        <div class="sidebar-menu">
          <ul id="accordion-menu">

            <li class="dropdown">
              <a href="javascript:;" class="dropdown-toggle">
                <span class="micon dw dw-house-1"></span><span class="mtext">Home</span>
              </a>
              <ul class="submenu">
                <li><router-link to="/" exact-active-class="active">Home</router-link></li>
                <li><router-link to="/about" exact-active-class="active">About</router-link></li>
              </ul>
            </li>

            <li>
              <router-link to="/users" class="dropdown-toggle no-arrow" exact-active-class="active" active-class="active">
                <i class="micon icon-copy dw dw-user-13"></i><span class="mtext">Users</span>
              </router-link>
            </li>


            <li> <div class="dropdown-divider"></div> </li>

          

            <li>
              <a href="#" @click.prevent="logoutUser" class="dropdown-toggle no-arrow">
                <span class="micon icon-copy ti-power-off"></span><span class="mtext">Logout</span>
              </a>
            </li>

          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex"

export default {
  name: "LeftSidebar",

  methods: {
    ...mapActions(['logout']),

    logoutUser: function() {

      this.$Swal.fire({
        title: 'Are you sure?',
        showCancelButton: true,
        cancelButtonColor: '#3085d6',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.isConfirmed) {

          this.logout()
          .then(() => {
            this.$router.push('/login')
          })
          .catch (err => {
            err = err.response.data
            this.$Toast.fire({ icon: 'error', title: err.message });
          })

        }
      }) 

    }
  }
};
</script>

<style scoped>

</style>
