<template>
  <div>
    <CategoriesList :categories="categories" />
  </div>
</template>
  
<script>
  import CategoriesList from '@/components/Categories/CategoriesList.vue';
  
  export default {
    name: 'CategoriesView',
    components: {
      CategoriesList,
    },
  };
</script>
  
<style scoped>

</style>
  