<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50">
    <!-- Navbar -->
    <nav class="p-4 bg-white shadow-lg sticky top-0 z-50">
      <div class="container mx-auto flex justify-between items-center">
        <div class="text-2xl font-bold text-indigo-600 animate-bounce">
          Mi SPA Chida
        </div>
        <div class="flex gap-6">
          <router-link
            to="/"
            class="relative font-semibold text-gray-700 hover:text-indigo-600 transition-colors duration-300"
          >
            Inicio
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-300 hover:w-full"></span>
          </router-link>
          <router-link
            to="/import"
            class="relative font-semibold text-gray-700 hover:text-indigo-600 transition-colors duration-300"
          >
            Importar Excel
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-300 hover:w-full"></span>
          </router-link>
          <router-link
            to="/export"
            class="relative font-semibold text-gray-700 hover:text-indigo-600 transition-colors duration-300"
          >
            Exportar SQL
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 transition-all duration-300 hover:w-full"></span>
          </router-link>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto mt-10 p-4">
      <transition name="fade-slide" mode="out-in">
        <router-view />
      </transition>
    </main>
  </div>
</template>

<style scoped>
/* Animación de fade + slide para el router-view */
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: all 0.5s ease;
}
.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(20px);
}
.fade-slide-enter-to {
  opacity: 1;
  transform: translateY(0);
}
.fade-slide-leave-from {
  opacity: 1;
  transform: translateY(0);
}
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(20px);
}
</style>
