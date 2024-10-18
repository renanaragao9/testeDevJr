<template>
    <div>
      <DashboardList :dashboard="dashboards" />
    </div>
  </template>
    
  <script>
    import DashboardList from '@/components/Dashboard/DashboardList.vue';
    
    export default {
      name: 'DashboardView',
      components: {
        DashboardList,
      },
    };
  </script>
    
  <style scoped>
  
  </style>
    