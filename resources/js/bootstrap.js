import axios from 'axios';

window.axios = axios.create({
  baseURL: import.meta.env.VITE_APP_URL || 'http://localhost:8000', // URL de tu backend Laravel
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
});
