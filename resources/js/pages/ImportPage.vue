<template>
  <div class="max-w-5xl mx-auto p-6 space-y-6">
    <!-- Título -->
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center animate-fadeIn">
      Importar Excel y Generar SQL
    </h1>

    <!-- Sección de ejemplo de Excel -->
    <div class="bg-blue-50 shadow rounded-lg p-6 animate-fadeInUp">
      <h2 class="text-xl font-semibold mb-4">Ejemplo de Estructura del Excel</h2>
      <p class="mb-3 text-gray-700">
        Asegúrate de que tu archivo Excel tenga exactamente estos encabezados y columnas:
      </p>
      <div class="overflow-auto">
        <table class="min-w-full border-collapse border border-blue-200 animate-pulse">
          <thead class="bg-blue-200">
            <tr>
              <th class="px-3 py-2 border">Nombre</th>
              <th class="px-3 py-2 border">Apellido Paterno</th>
              <th class="px-3 py-2 border">Apellido Materno</th>
              <th class="px-3 py-2 border">Correo Electrónico</th>
              <th class="px-3 py-2 border">Dirección</th>
              <th class="px-3 py-2 border">Teléfono</th>
            </tr>
          </thead>
          <tbody>
            <tr class="bg-blue-100">
              <td class="px-3 py-1 border text-sm text-gray-600">Juan</td>
              <td class="px-3 py-1 border text-sm text-gray-600">Pérez</td>
              <td class="px-3 py-1 border text-sm text-gray-600">Gómez</td>
              <td class="px-3 py-1 border text-sm text-gray-600">juan@example.com</td>
              <td class="px-3 py-1 border text-sm text-gray-600">Calle 123</td>
              <td class="px-3 py-1 border text-sm text-gray-600">5551234567</td>
            </tr>
            <tr class="bg-blue-50">
              <td class="px-3 py-1 border text-sm text-gray-600">María</td>
              <td class="px-3 py-1 border text-sm text-gray-600">López</td>
              <td class="px-3 py-1 border text-sm text-gray-600">Ramírez</td>
              <td class="px-3 py-1 border text-sm text-gray-600">maria@example.com</td>
              <td class="px-3 py-1 border text-sm text-gray-600">Av. Reforma 456</td>
              <td class="px-3 py-1 border text-sm text-gray-600">5559876543</td>
            </tr>
          </tbody>
        </table>
      </div>
      <p class="mt-2 text-gray-500 text-sm italic">
        Solo los encabezados importan, las filas son de ejemplo.
      </p>
    </div>

    <!-- Sección de subida de archivo -->
    <div class="bg-white shadow rounded-lg p-6 animate-fadeInUp delay-200">
      <h2 class="text-xl font-semibold mb-4">Selecciona un archivo Excel</h2>
      <div class="flex flex-col sm:flex-row gap-4 items-start">
        <input
          type="file"
          @change="onFile"
          accept=".xlsx,.xls"
          class="border border-gray-300 rounded px-3 py-2 w-full sm:w-auto"
        />
        <button
          class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded disabled:opacity-50 transition-all duration-300 transform hover:scale-105"
          @click="upload"
          :disabled="!file"
        >
          Subir y Previsualizar
        </button>
      </div>

      <!-- Spinner de carga -->
      <div v-if="loading" class="mt-4 flex items-center gap-3">
        <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent border-b-transparent rounded-full animate-spin"></div>
        <span class="text-blue-600 font-medium text-lg animate-pulse">Procesando archivo...</span>
      </div>
    </div>

    <!-- Sección de previsualización -->
    <div v-if="headers.length" class="bg-white shadow rounded-lg p-6 animate-fadeInUp delay-300">
      <h2 class="text-xl font-semibold mb-4">Previsualización de Datos</h2>

      <!-- Cabeceras -->
      <div class="mb-4">
        <h3 class="font-medium mb-2">Cabeceras</h3>
        <div class="flex flex-wrap gap-2">
          <span
            v-for="(h, i) in headers"
            :key="i"
            class="px-2 py-1 bg-gray-200 rounded font-mono text-sm animate-pulse"
          >
            {{ h }}
          </span>
        </div>
      </div>

      <!-- Filas (preview) -->
      <div class="overflow-auto max-h-64 border rounded">
        <table class="min-w-full border-collapse">
          <thead class="bg-gray-100 sticky top-0">
            <tr>
              <th
                v-for="(h, i) in headers"
                :key="i"
                class="px-3 py-2 border text-left font-medium text-gray-700"
              >
                {{ h }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, i) in rows" :key="i" class="odd:bg-gray-50">
              <td
                v-for="(h, j) in headers"
                :key="j"
                class="px-3 py-1 border text-gray-700 text-sm"
              >
                {{ row[h] }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Acciones -->
      <div class="mt-6 flex flex-col sm:flex-row gap-4 items-start">
        <input
          v-model="tableName"
          placeholder="Nombre de tabla destino (ej. mi_tabla)"
          class="border px-3 py-2 rounded w-full sm:w-auto flex-1"
        />
        <button
          class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded disabled:opacity-50 transition-all duration-300 transform hover:scale-105"
          @click="generateSql"
          :disabled="!tableName"
        >
          Generar SQL
        </button>
        <button
          class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded disabled:opacity-50 transition-all duration-300 transform hover:scale-105"
          @click="saveToDb"
          :disabled="!tableName"
        >
          Guardar en BD
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const file = ref(null);
const headers = ref([]);
const rows = ref([]);
const loading = ref(false);
const tableName = ref("");

const onFile = (e) => {
  file.value = e.target.files[0];
};

const upload = async () => {
  if (!file.value) return;
  loading.value = true;
  const fd = new FormData();
  fd.append("file", file.value);

  try {
    const res = await axios.post("/import-excel", fd, {
      headers: { "Content-Type": "multipart/form-data" },
    });
    headers.value = res.data.headers;
    rows.value = res.data.rows;
  } catch (err) {
    console.error(err);
    alert(err.response?.data?.message || "Error al procesar Excel");
  } finally {
    loading.value = false;
  }
};

const generateSql = async () => {
  try {
    const res = await axios.post(
      "/generate-sql",
      { table: tableName.value, rows: rows.value },
      { responseType: "blob" }
    );
    const url = window.URL.createObjectURL(new Blob([res.data]));
    const a = document.createElement("a");
    a.href = url;
    a.download = `${tableName.value}_insert.sql`;
    document.body.appendChild(a);
    a.click();
    a.remove();
  } catch (err) {
    console.error(err);
    alert("Error generando SQL");
  }
};

const saveToDb = async () => {
  if (!confirm(`¿Guardar filas en la tabla ${tableName.value}?`)) return;
  try {
    const res = await axios.post("/save-excel", {
      table: tableName.value,
      rows: rows.value,
    });
    alert(res.data.message || "Guardado correctamente");
  } catch (err) {
    console.error(err);
    alert(err.response?.data?.message || "Error guardando en BD");
  }
};
</script>

<style scoped>
/* Animaciones simples */
@keyframes fadeIn {
  0% { opacity: 0; transform: translateY(-10px); }
  100% { opacity: 1; transform: translateY(0); }
}

@keyframes fadeInUp {
  0% { opacity: 0; transform: translateY(20px); }
  100% { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn { animation: fadeIn 0.8s ease forwards; }
.animate-fadeInUp { animation: fadeInUp 0.8s ease forwards; }
.animate-pulse { animation: pulse 1.5s infinite; }

table tbody tr:hover {
  background-color: #f3f4f6;
}
</style>
