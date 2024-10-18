<template>
  <div id="app" @mousemove="resetTimer" @keydown="resetTimer">
    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar">
      <div class="container-fluid d-flex justify-content-between align-items-center">
        
        <!-- Botão de logout para dispositivos móveis -->
        <button
          class="btn btn-link logout-mobile d-lg-none me-3"
          v-if="isAuthenticated"
          @click="logout"
        >
          <i class="fas fa-sign-out-alt"></i> Sair
        </button>

        <!-- Logo centralizada -->
        <a class="navbar-brand mx-auto" href="/">
          <i class="fas fa-laptop-code"></i> Teste Dev Jr.
        </a>

        <!-- Botão do menu hambúrguer para dispositivos móveis -->
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Itens do menu navbar para desktop e mobile -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item" v-if="isAuthenticated">
              <span class="nav-link">
                <i class="fas fa-user"></i> Olá, {{ userName }}
              </span>
            </li>

            <li class="nav-item" v-if="!isAuthenticated">
              <router-link class="nav-link" to="/register">
                <i class="fas fa-user-plus"></i> Registro
              </router-link>
            </li>

            <li class="nav-item" v-if="!isAuthenticated">
              <router-link class="nav-link" to="/login">
                <i class="fas fa-sign-in-alt"></i> Login
              </router-link>
            </li>

            <li class="nav-item" v-if="isAuthenticated">
              <router-link class="nav-link" to="/">
                <i class="fas fa-tachometer-alt"></i> Dashboard
              </router-link>
            </li>

            <li class="nav-item" v-if="isAuthenticated">
              <router-link class="nav-link" to="/categorias">
                <i class="fas fa-tags"></i> Categorias
              </router-link>
            </li>

            <li class="nav-item" v-if="isAuthenticated">
              <router-link class="nav-link" to="/produtos">
                <i class="fas fa-box"></i> Produtos
              </router-link>
            </li>

            <!-- Botão de logout para desktop -->
            <li class="nav-item d-none d-lg-block" v-if="isAuthenticated">
              <button class="nav-link btn btn-link logout-desktop" @click="logout">
                <i class="fas fa-sign-out-alt"></i> Sair
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <router-view></router-view>

     <!-- Footer -->
     <footer class="footer mt-auto py-3">
      <div class="container text-center">
        <span class="text-muted">&copy; 2024 Teste Dev Jr. Todos os direitos reservados.</span>
      </div>
    </footer>
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
      timeoutDuration: 60 * 60 * 1000, // apos 60 minutos de inatividade a sessão do token sera encerrada
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
    this.startIdleTimer(); // inicia o tempo de inatividade
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
            
            withCredentials: true,
          });

          sessionStorage.removeItem('authToken');
          sessionStorage.removeItem('userName');

          sessionStorage.setItem('logoutMessage', 'Você saiu do sistema com sucesso.');

          this.$router.push({ name: 'Login' }).then(() => {
            window.location.reload();
          });

        } else {
          console.log('Nenhum token encontrado');
        }
      
      } catch (error) {
        console.error('Erro ao realizar logout:', error);
      }
    },
    
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

<style scoped>

  body {
    background-color: #f5f5f5;
    color: #333; 
  }

  .navbar {
    margin-bottom: 20px;
  }

  .custom-navbar {
    background-color: #333;
  }

  .custom-navbar .navbar-brand,
  .custom-navbar .nav-link,
  .custom-navbar .btn-link {
    color: #f8f9fa;
  }

  .custom-navbar .navbar-brand,
  .custom-navbar .nav-link:hover,
  .custom-navbar .btn-link:hover {
    color: #eaeaea;
  }

  .logout-mobile {
    color: #f8f9fa;
    text-decoration: none;
  }

  .logout-desktop {
    color: #f8f9fa;
    text-decoration: none;
  }

  body {
    background-color: #f5f5f5;
    color: #333;
  }
</style>
