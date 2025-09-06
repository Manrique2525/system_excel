<template>
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Exportar tabla a SQL</h2>
    <input v-model="table" placeholder="Nombre de tabla" class="border px-2 py-1 mr-2" />
    <button class="px-4 py-2 bg-green-600 text-white rounded" @click="exportSql">Descargar SQL</button>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const table = ref('');

const exportSql = async () => {
  if (!table.value) return alert('Escribe el nombre de la tabla');
  try {
    const res = await window.axios.get('/api/export-sql', {
      params: { table: table.value },
      responseType: 'blob'
    });
    const url = window.URL.createObjectURL(res.data);
    const a = document.createElement('a');
    a.href = url;
    a.download = `${table.value}_export.sql`;
    document.body.appendChild(a);
    a.click();
    a.remove();
  } catch (err) {
    console.error(err);
    alert('Error exportando');
  }
};
</script>
