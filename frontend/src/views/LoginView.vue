<template>
  <div class="flex items-center justify-center ">
    <div class="w-full max-w-md bg-white p-6 rounded shadow">
      <form @submit.prevent="handleLogin">
        <h2 class="text-xl font-semibold text-center mb-4">Login</h2>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-1" for="email">Email</label>
          <input
              v-model="email"
              type="email"
              id="email"
              class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400"
              placeholder="Enter your email"
              required
          />
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-1" for="password">Password</label>
          <input
              v-model="password"
              type="password"
              id="password"
              class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400"
              placeholder="Enter your password"
              required
          />
        </div>

        <button
            type="submit"
            :disabled="isLoading"
            class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition disabled:opacity-50"
        >
          <span v-if="isLoading">Logging in...</span>
          <span v-else>Login</span>
        </button>

        <p v-if="error" class="mt-4 text-red-500 text-sm text-center">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script>
import { useAuthStore} from "../stores/authStore.js";
import { mapStores } from 'pinia';
import { useRouter } from 'vue-router';

export default {
  name: 'LoginView',
  data() {
    return {
      email: '',
      password: '',
      isLoading: false,
      error: null,
    };
  },
  computed: {
    ...mapStores(useAuthStore),
  },
  setup() {
    const router = useRouter();
    return { router };
  },
  methods: {
    async handleLogin() {
      this.isLoading = true;
      this.error = null;

      try {
        await this.authStore.login(this.email, this.password);

        if (this.authStore.user) {
          this.router.push('/profile');
        } else {
          this.error = this.authStore.error || 'Login failed';
        }
      } catch (err) {
        this.error = 'Unexpected error during login.';
      } finally {
        this.isLoading = false;
      }
    },
  },
};
</script>
