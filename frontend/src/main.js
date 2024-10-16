import './assets/main.css'
import axios from 'axios';

axios.defaults.baseURL = import.meta.env.VITE_API_URL;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['Accept'] = 'application/json';

import { createApp } from 'vue'
import App from './App.vue'

createApp(App).mount('#app')
