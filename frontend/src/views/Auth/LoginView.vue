<template>
  <div>
    <Login /> <!-- Chamando o componente Login dentro da view -->
  </div>
</template>

<script>
import Login from '@/components/Auth/Login.vue';

export default {
  name: 'LoginView',
  components: {
    Login,
  },
};
</script>
