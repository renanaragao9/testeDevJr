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
      <h2 class="text-primary text-center mb-4">Registro</h2>
      <form @submit.prevent="registrar">
        <div class="mb-3">
          <label for="name" class="form-label">Nome</label>
          <input
            type="text"
            v-model="form.name"
            id="name"
            class="form-control"
            placeholder="Digite seu nome"
            required
          />
        </div>

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

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">Confirme a Senha</label>
          <input
            type="password"
            v-model="form.password_confirmation"
            id="password_confirmation"
            class="form-control"
            placeholder="Confirme sua senha"
            required
          />
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrar</button>

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

      <div v-if="token" class="mt-3 alert alert-success">
        Registro bem-sucedido! Seu token: {{ token }} <br />
        Redirecionando em 3 segundos...
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
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      },
      errors: [],
      token: null,
      isLoading: false,
    };
  },
  methods: {
    async registrar() {
      this.isLoading = true;
      try {
        this.errors = [];
        const response = await api.post('/register', this.form);
        this.token = response.data.token;

        sessionStorage.setItem('authToken', this.token);
        await this.getUserInfo();

        this.$router.push('/').then(() => {
          window.location.reload();
        });
      } catch (error) {
        if (error.response && error.response.data.errors) {
          this.errors = Object.values(error.response.data.errors).flat();
        } else {
          this.errors = ['Erro ao registrar. Tente novamente.'];
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

        sessionStorage.setItem('userName', response.data.name);
      } catch (error) {
        console.error('Erro ao obter dados do usuário:', error);
      }
    }
  }
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

.vh-100 {
  height: 100vh;
}

.card {
  border-radius: 10px;
  background-color: #ffffff;
}

h2 {
  font-size: 1.75rem;
  font-weight: 600;
}

.form-control {
  border-radius: 6px;
  border: 1px solid #ced4da;
}

.btn-primary {
  background-color: #007bff;
  border: none;
  transition: background-color 0.3s ease;
}

.btn-primary:hover {
  background-color: #0056b3;
}

.list-group-item-danger {
  color: #721c24;
  background-color: #f8d7da;
  border-color: #f5c6cb;
}
</style>
