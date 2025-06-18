<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white px-6 py-4 shadow-md">
      <div class="container mx-auto flex items-center justify-between">
        <div class="text-xl font-bold text-blue-700">
          MyApp
        </div>
        <div class="space-x-4">
          <RouterLink to="/" class="text-gray-700 hover:text-blue-600">Home</RouterLink>
          <RouterLink to="/login" class="text-gray-700 hover:text-blue-600">Login</RouterLink>
        </div>
      </div>
    </nav>

    <main class="container mx-auto px-6 py-10">
        <RouterView />
    </main>
  </div>
</template>

<script setup>
import { RouterLink, RouterView } from 'vue-router';
</script>