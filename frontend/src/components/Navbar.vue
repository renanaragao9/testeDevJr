<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="#">AslaFit</a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link class="nav-link" to="/home">Home</router-link>
          </li>
          <li class="nav-item" v-if="isAuthenticated">
            <span class="navbar-text">Olá, {{ userName }}</span>
          </li>
          <li class="nav-item" v-if="isAuthenticated">
            <button class="btn btn-danger nav-link" @click="logout">Logout</button>
          </li>
          <li class="nav-item" v-if="!isAuthenticated">
            <router-link class="nav-link" to="/login">Login</router-link>
          </li>
        </ul>
      </div>
    </nav>
  </template>
  
  <script>
  import { mapGetters, mapActions } from 'vuex';
  
  export default {
    computed: {
      ...mapGetters(['isAuthenticated', 'user']),
      userName() {
        return this.user ? this.user.name : '';
      }
    },
    methods: {
      ...mapActions(['logout']),
    }
  };
  </script>
  
  <style scoped>
  /* Estilize a navbar */
  .navbar {
    margin-bottom: 20px;
  }
  </style>
  