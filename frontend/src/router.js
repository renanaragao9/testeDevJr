import { createRouter, createWebHistory } from 'vue-router';
import TesteBackendView from './views/TesteBackendView.vue';
import RegisterView from './views/Auth/RegisterView.vue';
import LoginView from './views/Auth/LoginView.vue';

const routes = [
  {
    path: '/teste-backend',
    name: 'TesteBackend',
    component: TesteBackendView,
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterView,
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
