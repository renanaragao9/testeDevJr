import { createRouter, createWebHistory } from 'vue-router';
import TesteBackendView from './views/TesteBackendView.vue';
import RegisterView from './views/Auth/RegisterView.vue';
import LoginView from './views/Auth/LoginView.vue';
import CategoriesView from './views/Categories/CategoriesView.vue';


// Função para verificar se o usuário está autenticado
function isAuthenticated() {
  return !!sessionStorage.getItem('authToken'); // Retorna true se o token existir
}

const routes = [
  {
    path: '/categories',
    name: 'Categories',
    component: CategoriesView,
    beforeEnter: (to, from, next) => {
      if (isAuthenticated()) {
        next(); // Acesso permitido
      } else {
        next({ name: 'Login' }); // Redireciona para o login se não autenticado
      }
    },
  },
  {
    path: '/teste-backend',
    name: 'TesteBackend',
    component: TesteBackendView,
    beforeEnter: (to, from, next) => {
      // Verifica se o usuário está autenticado antes de permitir o acesso
      if (isAuthenticated()) {
        next(); // Acesso permitido
      } else {
        next({ name: 'Login' }); // Redireciona para o login se não autenticado
      }
    },
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterView,
    beforeEnter: (to, from, next) => {
      // Verifica se o usuário já está autenticado
      if (!isAuthenticated()) {
        next(); // Acesso permitido
      } else {
        next({ name: 'TesteBackend' }); // Redireciona para o teste-backend se já autenticado
      }
    },
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView,
    beforeEnter: (to, from, next) => {
      // Verifica se o usuário já está autenticado
      if (!isAuthenticated()) {
        next(); // Acesso permitido
      } else {
        next({ name: 'TesteBackend' }); // Redireciona para o teste-backend se já autenticado
      }
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
