<template>
  <div id="app" @mousemove="resetTimer" @keydown="resetTimer">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Teste Dev</a>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <router-link class="nav-link" v-if="!isAuthenticated" to="/register">Registro</router-link>
            </li>

            <li class="nav-item" v-if="!isAuthenticated">
              <router-link class="nav-link" to="/login">Login</router-link>
            </li>
            
            <li class="nav-item">
              <router-link class="nav-link" v-if="isAuthenticated" to="/teste-backend">Teste Backend</router-link>
            </li>

            <li class="nav-item">
              <router-link class="nav-link" v-if="isAuthenticated" to="/">Dashboard</router-link>
            </li>

            <li class="nav-item">
              <router-link class="nav-link" v-if="isAuthenticated" to="/categorias">Categorias</router-link>
            </li>

            <li class="nav-item">
              <router-link class="nav-link" v-if="isAuthenticated" to="/produtos">Produtos</router-link>
            </li>
            
            <!-- Exibe o nome do usuário se autenticado -->
            <li class="nav-item" v-if="isAuthenticated">
              <span class="nav-link">Olá, {{ userName }}</span>
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
import api from '@/axios';
export default {
  name: 'App',
  data() {
    return {
      timeout: null,
      timeoutDuration: 60 * 60 * 1000, // Após 60 minutos de inatividade a sessão do token será encerrada
    };
  },
  computed: {
    isAuthenticated() {
      return !!sessionStorage.getItem('authToken');
    },
    userName() {
      return sessionStorage.getItem('userName');
    }
  },
  created() {
    this.startIdleTimer(); // Inicia o tempo de inatividade
  },
  methods: {
    async logout() {
      try {
        const token = sessionStorage.getItem('authToken');
        if (token) {
          await api.post('/logout', {}, {
            headers: {
              Authorization: `Bearer ${token}`,
            },
            withCredentials: true, // Garantir que o cookie seja enviado
          });

          // Remove o token e o nome do usuário do sessionStorage
          sessionStorage.removeItem('authToken');
          sessionStorage.removeItem('userName');

          // Redireciona para a página de login e recarrega a página
          this.$router.push({ name: 'Login' }).then(() => {
            window.location.reload(); // Recarrega a página para garantir que as credenciais sejam removidas
          });

        } else {
          console.log('Nenhum token encontrado');
        }
      } catch (error) {
        console.error('Erro ao realizar logout:', error);
      }
    },
    // Logica para logout apos os 60 minutos de inatividade
    resetTimer() {
      clearTimeout(this.timeout);
      this.startIdleTimer();
    },
    startIdleTimer() {
      this.timeout = setTimeout(() => {
        sessionStorage.removeItem('authToken');
        sessionStorage.removeItem('userName');
        this.$router.push({ name: 'Login' }).then(() => {
          window.location.reload();
        });
      }, this.timeoutDuration);
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
