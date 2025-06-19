<template>
  <div class="flex items-center justify-center  px-4">
    <div class="w-full max-w-7xl mx-auto bg-white p-6 rounded shadow">
      <div v-if="authStore.user">
        <h2 class="text-xl font-semibold text-center mb-4">Profile</h2>

        <div class="mb-4">
          <p class="text-sm text-gray-700"><strong>Name:</strong> {{ authStore.user.name }}</p>
        </div>

        <div class="mb-4">
          <p class="text-sm text-gray-700"><strong>Email:</strong> {{ authStore.user.email }}</p>
        </div>

        <NoteList v-if="userNotes?.length" :notes="userNotes" />
      </div>

      <div v-else class="text-center text-gray-500">
        <svg class="animate-spin h-6 w-6 mx-auto mb-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
        Loading profile...
      </div>
    </div>
  </div>
</template>

<script>
import { useAuthStore } from '../stores/authStore';
import { useMerchantStore } from "../stores/merchantStore";
import { mapStores } from 'pinia';
import NoteList from "../components/notes/NoteList.vue";

export default {
  name: 'ProfileView',
  components: {
    NoteList
  },
  data() {
    return {
      showForm: false,
      newNoteBody: '',
      isLoading: true
    };
  },
  computed: {
    ...mapStores(useAuthStore),
    userNotes() {
      return useMerchantStore().userNotes || [];
    }
  },
  async created() {
    if (!this.authStore.user) {
      await this.authStore.fetchUser();
    }

    if (this.authStore.user?.id) {
      const merchantStore = useMerchantStore();
      await merchantStore.fetchNotesByUser(this.authStore.user.id);
    }
  },
};
</script>
