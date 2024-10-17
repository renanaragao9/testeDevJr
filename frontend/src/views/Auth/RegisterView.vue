<template>
    <div>
      <Register /> <!-- Chamando o componente Register dentro da view -->
    </div>
  </template>
  
  <script>
  import Register from '@/components/Auth/Register.vue';
  
  export default {
    name: 'RegisterView',
    components: {
      Register,
    },
  };
  </script>
  