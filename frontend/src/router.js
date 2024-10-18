import { createRouter, createWebHistory } from 'vue-router';
import TesteBackendView                   from './views/TesteBackendView.vue';
import RegisterView                       from './views/Auth/RegisterView.vue';
import LoginView                          from './views/Auth/LoginView.vue';
import CategoriesView                     from './views/Categories/CategoriesView.vue';
import ProductsView                       from './views/Products/ProductsView.vue';
import DashboardView                      from './views/Dashboard/DashboardView.vue';

// Função para verificar se o usuário está autenticado
function isAuthenticated() {
  return !!sessionStorage.getItem('authToken');
}

const routes = [
  {
    path: '/',
    name: 'Dashboard',
    component: DashboardView,
    beforeEnter: (to, from, next) => {
      if (isAuthenticated()) {
        next();
      } else {
        next({ name: 'Login' });
      }
    },
  },
  {
    path: '/categorias',
    name: 'Categories',
    component: CategoriesView,
    beforeEnter: (to, from, next) => {
      if (isAuthenticated()) {
        next();
      } else {
        next({ name: 'Login' });
      }
    },
  },
  {
    path: '/produtos',
    name: 'Products',
    component: ProductsView,
    beforeEnter: (to, from, next) => {
      if (isAuthenticated()) {
        next();
      } else {
        next({ name: 'Login' });
      }
    },
  },
  {
    path: '/teste-backend',
    name: 'TesteBackend',
    component: TesteBackendView,
    beforeEnter: (to, from, next) => {
      if (isAuthenticated()) {
        next();
      } else {
        next({ name: 'Login' });
      }
    },
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterView,
    beforeEnter: (to, from, next) => {
      if (!isAuthenticated()) {
        next();
      } else {
        next({ name: 'TesteBackend' });
      }
    },
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView,
    beforeEnter: (to, from, next) => {
      if (!isAuthenticated()) {
        next();
      } else {
        next({ name: 'TesteBackend' });
      }
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
