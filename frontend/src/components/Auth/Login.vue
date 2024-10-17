<template>
  <div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg" style="width: 24rem;">
      <div class="text-center mb-4">
        <h2 class="text-primary">Login</h2>
      </div>
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

      <!-- Exibe o nome do usuário -->
      <div v-if="userName" class="mt-3 alert alert-success">
        <strong>Bem-vindo, {{ userName }}!</strong>
      </div>

      <!-- Exibe o token gerado -->
      <div v-if="token" class="mt-3 alert alert-info">
        <strong>Token:</strong> {{ token }}
      </div>

      <!-- Mensagem de sucesso -->
      <div v-if="message" class="mt-3 alert alert-success">
        {{ message }}
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',
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
    };
  },
  methods: {
    async login() {
      try {
        this.errors = []; // Limpa erros anteriores
        const response = await axios.post('http://localhost:8000/api/login', this.form, {
          withCredentials: true // Importante para o Laravel Sanctum com cookies
        });

        // Salva o token na variável e mostra na tela
        this.token = response.data.token;
        this.message = response.data.message; // Mensagem de sucesso
        localStorage.setItem('authToken', this.token);

        // Log no console para depuração
        console.log('Token:', this.token);

        // Requisição para pegar o nome do usuário autenticado
        await this.getUserInfo();

      } catch (error) {
        if (error.response && error.response.data.errors) {
          this.errors = Object.values(error.response.data.errors).flat();
        } else {
          this.errors = ['Erro ao realizar login. Tente novamente.'];
        }
      }
    },

    async getUserInfo() {
      try {
        const response = await axios.get('http://localhost:8000/api/user', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('authToken')}`,
          },
          withCredentials: true // Importante para a autenticação com Sanctum
        });

        // Exibe o nome do usuário
        this.userName = response.data.name;

        // Salva o nome no localStorage para persistência
        localStorage.setItem('userName', this.userName);
      } catch (error) {
        console.error('Erro ao obter dados do usuário:', error);
      }
    }
  }
};
</script>
  