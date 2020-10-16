<template>
  <div class="createUser">
    
    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title">
            <h4>Update User</h4>
          </div>
          <nav aria-label="breadcrumb" role="navigation">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><router-link to="/users">Users</router-link></li>
              <li class="breadcrumb-item active" aria-current="page">Update User</li>
            </ol>
          </nav>
        </div>
      
      </div>
    </div>

    <div class="pd-20 card-box mb-30">
      
    <form @submit.prevent="updateUser">
      <div class="form-group row" :class="{ 'has-danger': userForm.errors.has('name') }">
        <label class="col-sm-12 col-md-2 col-form-label">Name</label>
        <div class="col-sm-12 col-md-10">
          <input v-model="userForm.name" class="form-control" :class="{ 'form-control-danger': userForm.errors.has('name') }" type="text" placeholder="Harry Potter">
          <div class="form-control-feedback" v-if="userForm.errors.has('name')">{{userForm.errors.get('name')}}</div>
        </div>
      </div>
     
      <div class="form-group row" :class="{ 'has-danger': userForm.errors.has('email') }">
        <label class="col-sm-12 col-md-2 col-form-label">Email</label>
        <div class="col-sm-12 col-md-10">
          <input v-model="userForm.email" class="form-control" :class="{ 'form-control-danger': userForm.errors.has('email') }" type="email" placeholder="harrypotter@hogwarts.com">
          <div class="form-control-feedback" v-if="userForm.errors.has('email')">{{userForm.errors.get('email')}}</div>
        </div>
      </div>

      <div class="form-group row" :class="{ 'has-danger': userForm.errors.has('password') }">
        <label class="col-sm-12 col-md-2 col-form-label">Password</label>
        <div class="col-sm-12 col-md-10">
          <input v-model="userForm.password" class="form-control" :class="{ 'form-control-danger': userForm.errors.has('password') }" type="password" placeholder="password">
          <div class="form-control-feedback" v-if="userForm.errors.has('password')">{{userForm.errors.get('password')}}</div>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label">Confirm Password</label>
        <div class="col-sm-12 col-md-10">
          <input v-model="userForm.password_confirmation" class="form-control" type="password" placeholder="password confirmation">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-12 col-md-2 col-form-label"></label>
        <div class="col-sm-12 col-md-10">
          <button type="submit" class="btn btn-primary" :disabled="userForm.busy">
            <i v-if="userForm.busy" class="icon-copy fa fa-spinner fa-spin" aria-hidden="true"></i>
            <i v-else class="icon-copy dw dw-add-user"></i> 
            Update User 
          </button>
        </div>
      </div>


    
    </form>
							
    </div>

  </div>
</template>

<script>
import { Form } from 'vform'
import { mapGetters, mapActions } from 'vuex'
export default {

  name: "CreateUser",

  computed: mapGetters(['allUsers']),

  data() {
    return {
      userForm: new Form({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      }),
    }
  },

  created() {
      this.fetchUser()
  },


  mounted() {
      
  },

  methods: {
    ...mapActions(['updateUserIfExists']),

    fetchUser() {

        let userId = this.$route.params.userId

        let user = this.allUsers.data.find(x => x.id === userId);

        if (user != undefined) {
          this.userForm.fill(user)
        }
        else {
          let url = `http://localhost:8000/api/users/${userId}`
  
          this.$axios.get(url)
          .then(res => {
            this.userForm.fill(res.data.data)
          })
          .catch (err => {
            this.$Toast.fire({ icon: 'error', title: err.response.data.message });
          })
        }
    },

    updateUser() {
      let userId = this.$route.params.userId

      let url = `http://localhost:8000/api/users/${userId}`

      this.userForm.put(url)
      .then(res => {
        this.updateUserIfExists(res.data.data)
        this.$Toast.fire({ icon: 'success', title: res.data.message });

      })
      .catch(err => {
        if (err.response.status != 422) {
          this.$Toast.fire({ icon: 'error', title: err.response.data.message });
        }
      })
    }

  },

  

  
};
</script>
