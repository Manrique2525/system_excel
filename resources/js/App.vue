<template>
  <div>
    <nav class="p-4 bg-white shadow">
      <div class="container mx-auto flex gap-4">
        <router-link to="/" class="font-semibold">Inicio</router-link>
        <router-link to="/import">Importar Excel</router-link>
        <router-link to="/export">Exportar SQL</router-link>
      </div>
    </nav>

    <main class="container mx-auto mt-6">
      <router-view />
    </main>
  </div>
</template>
