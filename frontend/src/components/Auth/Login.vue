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

      <div v-if="userName" class="mt-3 alert alert-success">
        <strong>Bem-vindo, {{ userName }}!</strong>
      </div>

      <div v-if="token" class="mt-3 alert alert-info">
        <strong>Token:</strong> {{ token }}
      </div>

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
        this.token = response.data.token || ''; // Garantindo que token tenha valor padrão
        this.message = response.data.message || 'Login realizado com sucesso!'; // Mensagem de sucesso
        sessionStorage.setItem('authToken', this.token); // Armazena o token no sessionStorage

        // Log no console para depuração
        console.log('Token:', this.token);

        // Requisição para pegar o nome do usuário autenticado
        await this.getUserInfo();

        // Redireciona para a rota "teste-backend" após login bem-sucedido
        this.$router.push('/teste-backend').then(() => {
          window.location.reload(); // Recarrega a página para garantir que as informações sejam atualizadas
        });

      } catch (error) {
        // Captura a mensagem de erro de acordo com o código de status
        if (error.response && error.response.status === 429) {
          this.errors = [error.response.data.message]; // Mensagem personalizada para o erro 429
        } else if (error.response && error.response.data.errors) {
          this.errors = Object.values(error.response.data.errors).flat(); // Captura erros de validação
        } else {
          this.errors = ['Erro ao realizar login. Tente novamente.']; // Mensagem genérica
        }
      }
    },

    
    async getUserInfo() {
      try {
        const response = await axios.get('http://localhost:8000/api/user', {
          headers: {
            Authorization: `Bearer ${sessionStorage.getItem('authToken')}`, // Obtenha o token do sessionStorage
          },
          withCredentials: true // Importante para a autenticação com Sanctum
        });

        // Exibe o nome do usuário
        this.userName = response.data.name || ''; // Garantindo que userName tenha valor padrão

        // Salva o nome no sessionStorage para persistência
        sessionStorage.setItem('userName', this.userName);
      } catch (error) {
        console.error('Erro ao obter dados do usuário:', error);
      }
    }
  }
};
</script>
