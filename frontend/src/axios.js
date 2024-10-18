import axios from 'axios';

// requisições api
const api = axios.create({
  baseURL: 'http://localhost:8000/api', // Aqui define a URL base
});

export default api;