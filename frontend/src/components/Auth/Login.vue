<template>
  <div v-if="isLoading" class="d-flex justify-content-center align-items-center">
    <div class="loading-dots">
      <span class="dot"></span>
      <span class="dot"></span>
      <span class="dot"></span>
    </div>
  </div>

  <div v-else class="d-flex justify-content-center align-items-center">
    <div class="card p-4 shadow-lg" style="width: 24rem;">
      <h2 class="text-primary text-center mb-4">Login</h2>

      <form @submit.prevent="login">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input
            type="email"
            v-model="form.email"
            id="email"
            class="form-control"
            placeholder="Digite seu email"
            required
          />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Senha</label>
          <input
            type="password"
            v-model="form.password"
            id="password"
            class="form-control"
            placeholder="Digite sua senha"
            required
          />
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>

        <div v-if="errors.length" class="mt-3">
          <ul class="list-group">
            <li
              v-for="(error, index) in errors"
              :key="index"
              class="list-group-item list-group-item-danger"
            >
              {{ error }}
            </li>
          </ul>
        </div>
      </form>

      <div v-if="userName" class="mt-3 alert alert-success">
        <strong>Bem-vindo, {{ userName }}!</strong>
      </div>

      <div v-if="message" class="mt-3 alert alert-success">
        {{ message }}
      </div>

      <div v-if="logoutMessage" class="mt-3 alert alert-success">
        {{ logoutMessage }}
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import api from '@/axios';

export default {
  data() {
    return {
      form: {
        email: '',
        password: '',
      },
      errors: [],
      token: '',
      message: '',
      userName: '',
      logoutMessage: '',
      isLoading: true,
    };
  },
  mounted() {
    setTimeout(() => {
      this.isLoading = false;
    }, 1000);

    this.logoutMessage = sessionStorage.getItem('logoutMessage') || '';
    sessionStorage.removeItem('logoutMessage');
  },
  methods: {
    async login() {
      
      this.isLoading = true;
      
      try {
        this.errors = [];
        const response = await api.post('/login', this.form, {
          withCredentials: true,
        });

        this.token = response.data.token || '';
        this.message = response.data.message || 'Login realizado com sucesso!';
        sessionStorage.setItem('authToken', this.token);

        await this.getUserInfo();

        this.$router.push('/').then(() => {
          window.location.reload();
        });
      
      } catch (error) {
        if (error.response && error.response.status === 429) {
          this.errors = [error.response.data.message];
        
        } else if (error.response && error.response.data.errors) {
          this.errors = Object.values(error.response.data.errors).flat();
        
        } else {
          this.errors = ['Erro ao realizar login. Tente novamente.'];
        
        }
      
      } finally {
        this.isLoading = false;
      }
    },

    async getUserInfo() {
      try {
        const response = await api.get('/user', {
          headers: {
            Authorization: `Bearer ${sessionStorage.getItem('authToken')}`,
          },
          withCredentials: true,
        });

        this.userName = response.data.name || '';
        sessionStorage.setItem('userName', this.userName);
      } catch (error) {
        console.error('Erro ao obter dados do usuário:', error);
      }
    },
  },
};
</script>

<style scoped>
body, html {
  margin: 0;
  padding: 0;
  height: 100%;
  overflow: hidden;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Aplicando a fonte da Microsoft */
}

.loading-dots {
  display: flex;
  justify-content: center;
  align-items: center;
}

.dot {
  height: 12px;
  width: 12px;
  margin: 0 5px;
  background-color: #0078d4; /* Cor do ponto */
  border-radius: 50%;
  animation: loading 1s infinite ease-in-out;
}

.dot:nth-child(1) {
  animation-delay: 0s;
}

.dot:nth-child(2) {
  animation-delay: 0.2s;
}

.dot:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes loading {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.5);
  }
}
</style>
