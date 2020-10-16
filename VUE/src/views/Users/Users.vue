<template>
  <div class="users">

    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title">
            <h4>List of Users</h4>
          </div>
          <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active" aria-current="page">Users</li>
            </ol>
          </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <a href="#" @click.prevent="$router.push({name: 'UserCreate'})" class="btn btn-primary btn-sm scroll-click" rel="content-y" data-toggle="collapse" role="button">
            <i class="icon-copy dw dw-add-user"></i> Add User
          </a>
        </div>
      </div>
    </div>

    <div class="pd-20 card-box mb-30">
 
      <div class="table-responsive">
        <table class="table table-striped table-borderless">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Added On</th>
              <th scope="col">Option</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in allUsers.data" :key="user.id">
              <th scope="row">{{user.id}}</th>
              <td scope="row">{{user.name}}</td>
              <td scope="row">{{user.email}}</td>
              <td scope="row">{{user.created_at | formatDate}}</td>

              <td scope="row">
                <div class="btn-group dropdown">
                  <button :disabled="loaders.includes(user.id)" type="button" class="btn btn-light btn-sm dropdown-toggle waves-effect btn-block" data-toggle="dropdown" aria-expanded="false"> 
                    Options <span class="caret"></span>
                  </button>
                  <div class="dropdown-menu" style="">
                    <a class="dropdown-item" href="#" @click.prevent="$router.push({name: 'UserUpdate', params: {userId: user.id}})">
                      <i class="icon-copy fa fa-edit" aria-hidden="true"></i> Update
                    </a>
                    <a class="dropdown-item" href="#" @click.prevent="deleteUser(user.id)">
                      <i class="fa fa-trash" aria-hidden="true"></i> Delete
                    </a>
                  </div>
                </div>
              </td>

            </tr>
          </tbody>
        </table>
      </div>


      <div class="btn-group mb-15">
          <button type="button" class="btn btn-light btn-sm" 
            :disabled="allUsers.prev_page_url == null" 
            @click="callFetchUsers(allUsers.prev_page_url)"
          >Prev</button>

          <button type="button" class="btn btn-light btn-sm" disabled>{{allUsers.current_page}} of {{allUsers.last_page}}</button>
          
          <button type="button" class="btn btn-light btn-sm" 
            :disabled="allUsers.next_page_url == null" 
            @click="callFetchUsers(allUsers.next_page_url)"
          >Next</button>
        </div>

    </div>

        
  </div>
</template>

<script>
import {mapGetters, mapActions} from "vuex"


export default {
  name: "Users",

  computed: mapGetters(['allUsers']),

  data () {
    return {
      filter: {
        limit: 10,
        sort_by: "id",
        sort_order: "desc"
      },

      loaders: []
    }
  },

  created() {

      this.callFetchUsers()

  },

  mounted() {
    
    this.$channel.bind('user-added', () => {
      this.callFetchUsers()
    })

    this.$channel.bind('user-deleted', () => {
      this.callFetchUsers()
    })

    this.$channel.bind('user-updated', () => {
      this.callFetchUsers()
    })

  },

  methods: {
    ...mapActions(['fetchUsers', 'updateUserIfExists']),

    callFetchUsers(url=null) {
      
      this.fetchUsers([this.filter, url])

      .then(() => {
     
      })

      .catch(err => {
        this.$Toast.fire({ icon: 'error', title: err.message });
      })

    },


    deleteUser(userId)
    {
      this.$Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#3085d6',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes'
      }).then((result) => {
        if (result.isConfirmed) {

          // putting id of the user in loader.delete to show the loader icon in the delete button for that user.
          this.loaders.push(userId)

          let url = `http://localhost:8000/api/users/${userId}`

          this.$axios.delete(url, {socket_id: this.socket_id})
          .then((res) => {
            this.$Toast.fire({ icon: 'success', title: res.data.message });
            this.callFetchUsers()
          })
          .catch (err => {
            this.$Toast.fire({ icon: 'error', title: err.response.data.message });
          })
          .finally(() => {
            // removing id of the user from loader.delete to remove the loader icon in the delete button for that user.
            let index = this.loaders.indexOf(userId);
            this.loaders.splice(index, 1);
          })
        }
      })
    }
  }
}
</script>