import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/Home.vue";
import store from "../store/index.js"

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Home",
    meta: {
      auth: true
    },
    component: Home
  },
  {
    path: "/about",
    name: "About",
    meta: {
      auth: true
    },
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/About.vue")
  },
  {
    path: "/login",
    name: "Login",
    component: () =>
      import("../views/Login.vue")
  },
  {
    path: "/users",
    name: "Users",
    meta: {
      auth: true
    },
    component: () =>
      import("../views/Users/Users.vue")
  },
  {
    path: "/users/create",
    name: "UserCreate",
    meta: {
      auth: true
    },
    component: () =>
      import("../views/Users/CreateUser.vue")
  },
  {
    path: "/users/:userId/update",
    name: "UserUpdate",
    meta: {
      auth: true
    },
    component: () =>
      import("../views/Users/UpdateUser.vue")
  }
];

const router = new VueRouter({
  routes
});

router.beforeEach((to, from, next) => {
  if (to.meta.auth && !store.getters.isLoggedIn) {
    next({
      path: '/login',
      query: { redirect: to.fullPath }
    })
  }
  else {
    next()
  }  
})

export default router;
