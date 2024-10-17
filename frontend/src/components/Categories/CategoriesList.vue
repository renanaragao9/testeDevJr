<template>
    <div>
      <h2 class="text-primary">Lista de Categorias</h2>
      <ul>
        <li v-for="category in categories" :key="category.id">
          {{ category.name }}
        </li>
      </ul>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'CategoriesList',
    data() {
      return {
        categories: [], // Inicializa a lista de categorias como um array vazio
      };
    },
    mounted() {
      this.fetchCategories(); // Chama a função para buscar categorias ao montar o componente
    },
    methods: {
        async fetchCategories() {
            try {
            const token = sessionStorage.getItem('authToken'); // Obtém o token de autenticação
            const apiUrl = 'http://localhost:8000/api'; // URL da API

            console.log('Token utilizado:', token); // Adicione este log para verificar o token

            const response = await axios.get(`${apiUrl}/categories`, {
                headers: {
                Authorization: `Bearer ${token}`, // Envia o token no cabeçalho da requisição
                },
            });

            console.log('Categorias recebidas:', response.data); // Log para verificar os dados recebidos
            this.categories = response.data; // Atualiza a lista de categorias
            } catch (error) {
            console.error('Erro ao buscar categorias:', error.response ? error.response.data : error.message); // Log detalhado do erro
            }
        },
    }
  };
  </script>
  
  <style scoped>
  /* Adicione estilos personalizados se necessário */
  </style>
  