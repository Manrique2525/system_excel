<template>
  <div class="min-h-screen bg-gray-50 flex flex-col">
    <!-- Hero / Banner -->
    <section class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white py-20 relative overflow-hidden">
      <div class="container mx-auto text-center px-4 relative z-10">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-fadeInDown">Bienvenido a tu SPA Laravel + Vue</h1>
        <p class="text-lg md:text-xl animate-fadeIn delay-200">
          Sistema para importar Excel, generar SQL y gestionar datos de manera eficiente
        </p>
      </div>
      <!-- Decoración animada -->
      <div class="absolute top-0 left-0 w-72 h-72 bg-white opacity-10 rounded-full animate-pulse"></div>
      <div class="absolute bottom-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full animate-pulse delay-500"></div>
    </section>

    <!-- Cards de acciones -->
    <section class="flex-1 container mx-auto py-12 px-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Card Importar Excel -->
      <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition duration-500 transform hover:-translate-y-2 hover:scale-105">
        <div class="flex items-center mb-4">
          <svg class="w-10 h-10 text-blue-500 animate-bounce mr-3" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path>
          </svg>
          <h2 class="text-2xl font-semibold text-gray-800">Importar Excel</h2>
        </div>
        <p class="text-gray-600 mb-4">Carga tus archivos Excel y previsualiza los datos antes de generar SQL.</p>
        <router-link
          to="/import"
          class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium transition duration-300"
        >
          Ir a Importar
        </router-link>
      </div>

      <!-- Card Generar SQL -->
      <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition duration-500 transform hover:-translate-y-2 hover:scale-105">
        <div class="flex items-center mb-4">
          <svg class="w-10 h-10 text-green-500 animate-bounce mr-3" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7-7-7M5 10l7-7 7 7"></path>
          </svg>
          <h2 class="text-2xl font-semibold text-gray-800">Generar SQL</h2>
        </div>
        <p class="text-gray-600 mb-4">Convierte tus datos en sentencias SQL listas para insertar en tu base de datos.</p>
        <router-link
          to="/export"
          class="inline-block px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-medium transition duration-300"
        >
          Ir a Exportar
        </router-link>
      </div>

      <!-- Card Dashboard / Información -->
      <div class="bg-white shadow-lg rounded-lg p-6 hover:shadow-2xl transition duration-500 transform hover:-translate-y-2 hover:scale-105">
        <div class="flex items-center mb-4">
          <svg class="w-10 h-10 text-indigo-500 animate-bounce mr-3" fill="none" stroke="currentColor" stroke-width="2"
               viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v18H3V3z"></path>
          </svg>
          <h2 class="text-2xl font-semibold text-gray-800">Dashboard</h2>
        </div>
        <p class="text-gray-600 mb-4">Visualiza un resumen de tus datos y acciones recientes desde aquí.</p>
        <router-link
          to="/dashboard"
          class="inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-medium transition duration-300"
        >
          Ver Dashboard
        </router-link>
      </div>
    </section>

    <!-- Footer simple -->
    <footer class="bg-gray-100 py-6 mt-auto">
      <div class="container mx-auto text-center text-gray-500">
        &copy; {{ new Date().getFullYear() }} Sistema Excel-SQL | Hecho con ❤️ en Laravel + Vue
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: "Home",
};
</script>

<style scoped>
@keyframes fadeInDown {
  0% { opacity: 0; transform: translateY(-20px); }
  100% { opacity: 1; transform: translateY(0); }
}

@keyframes fadeIn {
  0% { opacity: 0; }
  100% { opacity: 1; }
}

.animate-fadeInDown {
  animation: fadeInDown 1s ease-out forwards;
}

.animate-fadeIn {
  animation: fadeIn 1s ease-out forwards;
  animation-delay: 0.3s;
}

.animate-bounce {
  animation: bounce 1.5s infinite;
}

@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}
</style>
