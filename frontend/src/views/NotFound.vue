<template>
    <div class="error-container">
      <img src="/images/404 Error-pana.png" alt="Página não encontrada" class="img-fluid" />
      <h5>Página não encontrada!</h5>
      <router-link to="/" class="btn btn-dark">Voltar para a página inicial</router-link>
    </div>
  </template>
  
  <script>
  export default {
    name: 'YourView',
  };
  </script>
  
  <style scoped>
  html, body {
    height: 100%;    margin: 0;
    overflow: hidden;
  }
  
  .error-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    max-width: 600px;
    margin: auto;
    text-align: center;
    background-color: #f8f9fa;
    color: #343a40;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }
  
  img {
    max-width: 50%; /* Diminui o tamanho da imagem */
    height: auto;
  }
  
  .btn:hover {
    background-color: #0056b3;
  }
  </style>
  