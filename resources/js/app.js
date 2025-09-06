import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // <- agregar esto

createApp(App)
  .use(router)
  .mount('#app');
