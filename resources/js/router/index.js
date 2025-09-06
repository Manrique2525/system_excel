import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/pages/Home.vue';
import ImportPage from '@/pages/ImportPage.vue';
import ExportPage from '@/pages/ExportPage.vue';

const routes = [
  { path: '/', component: Home, name: 'home' },
  { path: '/import', component: ImportPage, name: 'import' },
  { path: '/export', component: ExportPage, name: 'export' },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
