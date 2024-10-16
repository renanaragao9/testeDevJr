<template>
    <div>
      <h1>Teste de Comunicação com o Backend</h1>
      <button @click="testarComunicacao">Testar Comunicação</button>
      <p>{{ mensagem }}</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'TesteBackend',
    data() {
      return {
        mensagem: ''
      };
    },
    methods: {
      testarComunicacao() {
        axios.get('http://127.0.0.1:8000/api/teste-rota') // Atualize aqui
          .then(response => {
            this.mensagem = response.data.message;
          })
          .catch(error => {
            console.error('Erro ao se comunicar com o backend:', error);
            this.mensagem = 'Erro ao se comunicar com o backend';
          });
      }
    }
  };
  </script>
  
  <style scoped>
  /* Estilos opcionais para o componente */
  button {
    margin: 10px 0;
    padding: 8px 12px;
    background-color: #42b983;
    color: white;
    border: none;
    cursor: pointer;
  }
  
  button:hover {
    background-color: #2c8c66;
  }
  </style>
  