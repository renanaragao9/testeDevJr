<template>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Teste Dev</a>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link class="nav-link" to="/register">Registro</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/teste-backend">Teste Backend</router-link>
            </li>
            <!-- Exibe o nome do usuário se autenticado -->
            <li class="nav-item" v-if="isAuthenticated">
              <span class="nav-link">Olá, {{ userName }}</span>
            </li>
            <li class="nav-item" v-if="!isAuthenticated">
              <router-link class="nav-link" to="/login">Login</router-link>
            </li>
            <!-- Botão de logout se autenticado -->
            <li class="nav-item" v-if="isAuthenticated">
              <button class="nav-link btn btn-link" @click="logout">Logout</button>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <router-view></router-view>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'App',
  computed: {
    isAuthenticated() {
      return !!localStorage.getItem('authToken'); // Verifica se o token existe
    },
    userName() {
      return localStorage.getItem('userName'); // Obtém o nome do usuário salvo no localStorage
    }
  },
  methods: {
    async logout() {
      try {
        const token = localStorage.getItem('authToken');
        if (token) {
          // Envia a requisição para o logout no servidor
          await axios.post('http://localhost:8000/api/logout', {}, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
            withCredentials: true, // Garantir que o cookie seja enviado
          });

          // Remove o token e o nome do usuário do localStorage
          localStorage.removeItem('authToken');
          localStorage.removeItem('userName');

          // Redireciona para a página de login e recarrega a página
          this.$router.push({ name: 'Login' }).then(() => {
            window.location.reload();  // Recarrega a página para garantir que as credenciais sejam removidas
          });

        } else {
          console.log('Nenhum token encontrado');
        }
      } catch (error) {
        console.error('Erro ao realizar logout:', error);
      }
    }
  }
};
</script>

<style>
/* Estilos da navbar */
.navbar {
  margin-bottom: 20px;
}
</style>
