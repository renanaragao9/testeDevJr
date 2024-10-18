import axios from 'axios';

// requisições api
const api = axios.create({
  baseURL: 'http://localhost:8000/api', // Aqui define a URL base
});

// requisições de imagens
const storage = axios.create({
    baseURL: 'http://localhost:8000/storage',  // URL base para o storage
});

export default api;