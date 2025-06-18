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

          <template v-if="auth.user">
            <RouterLink to="/profile" class="text-gray-700 hover:text-blue-600">Profile</RouterLink>
            <button @click="handleLogout" class="text-red-500 hover:text-red-700">Logout</button>
          </template>

          <template v-else>
            <RouterLink to="/login" class="text-gray-700 hover:text-blue-600">Login</RouterLink>
          </template>
        </div>
      </div>
    </nav>

    <main class="container mx-auto px-6 py-10">
      <RouterView />
    </main>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { RouterLink, RouterView } from 'vue-router';
import { useAuthStore} from "./stores/authStore.js";
import router from "./router/index.js";

const auth = useAuthStore();

const handleLogout = async () => {
  await auth.logout();
  await router.push('/');
};

onMounted(() => {
  auth.fetchUser(); // Keep user in sync if session still valid
});
</script>
