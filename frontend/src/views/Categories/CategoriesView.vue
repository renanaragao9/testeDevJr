<template>
    <div>
      <h1>Categories</h1>
      <CategoriesList :categories="categories" />
    </div>
  </template>
  
  <script>
  import CategoriesList from '@/components/Categories/CategoriesList.vue';
  import axios from 'axios';
  
  export default {
    name: 'CategoriesView',
    components: {
      CategoriesList,
    },
    data() {
      return {
        categories: [],
      };
    },
    created() {
      this.fetchCategories();
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
  