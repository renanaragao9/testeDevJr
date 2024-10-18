//import './assets/main.css';
import axios from 'axios';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import 'bootstrap/dist/css/bootstrap.min.css';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

// Configurações do backend
axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['Accept'] = 'application/json';

// Crie a instância do app Vue e utilize o router
const app = createApp(App);

app.use(router); // Utilize o router na aplicação

app.mount('#app');
