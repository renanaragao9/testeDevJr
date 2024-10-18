import { createRouter, createWebHistory } from 'vue-router';
import TesteBackendView                   from './views/TesteBackendView.vue';
import RegisterView                       from './views/Auth/RegisterView.vue';
import LoginView                          from './views/Auth/LoginView.vue';
import CategoriesView                     from './views/Categories/CategoriesView.vue';
import ProductsView                       from './views/Products/ProductsView.vue';
import DashboardView                      from './views/Dashboard/DashboardView.vue';
import NotFound                           from './views/NotFound.vue';

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
  { // Utilize para fazer testes de conexão com o backend
    path: '/aBcDeFgHiJkLmNoPqRsTuVwXyZ',
    name: 'TesteBackend',
    component: TesteBackendView,

  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterView,
    beforeEnter: (to, from, next) => {
      if (!isAuthenticated()) {
        next();
      } else {
        next({ name: 'Dashboard' });
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
        next({ name: 'Dashboard' });
      }
    },
  },
  {
    path: '/:catchAll(.*)', // Captura todas as rotas não definidas
    name: 'NotFound',
    component: NotFound,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
