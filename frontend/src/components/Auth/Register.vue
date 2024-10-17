<template>
    <div class="d-flex justify-content-center align-items-center vh-100">
      <div class="card p-4 shadow-lg" style="width: 24rem;">
        <div class="text-center mb-4">
          <!-- Substitua pela URL da sua logo -->
          <!-- <img src="/assets/logo.png" alt="Logo" class="logo mb-3" /> -->
          <h2 class="text-primary">Registro</h2>
        </div>
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
      };
    },
    methods: {
      async registrar() {
        try {
          this.errors = [];
          const response = await axios.post('/api/register', this.form);
          this.token = response.data.token;
  
          // Redireciona após 3 segundos
          setTimeout(() => {
            this.$router.push('/teste-backend');
          }, 3000);
        } catch (error) {
          if (error.response && error.response.data.errors) {
            this.errors = Object.values(error.response.data.errors).flat();
          } else {
            this.errors = ['Erro ao registrar. Tente novamente.'];
          }
        }
      }
    }
  };
  </script>
  
  <style scoped>
  /* Estilos adicionais */
  .vh-100 {
    height: 100vh;
  }
  
  .card {
    border-radius: 10px;
    background-color: #ffffff;
  }
  
  .logo {
    width: 100px;
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
  