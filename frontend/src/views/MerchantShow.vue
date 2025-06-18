<template>
  <div class="w-full px-4 bg-gray-50">
    <div class="w-full max-w-7xl mx-auto bg-white p-6 rounded shadow">

      <!-- Loading spinner -->
      <div v-if="isLoading" class="flex justify-center items-center h-40">
        <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
        </svg>
      </div>

      <!-- Fade-in merchant section -->
      <transition name="fade">
        <div v-if="!isLoading && merchant">
          <MerchantItem :merchant="merchant" />

          <div class="flex items-center justify-between mt-8 mb-4">
            <h2 class="text-lg font-semibold">Notes</h2>
            <button
                @click="showForm = !showForm"
                class="bg-blue-500 text-white text-xs px-3 py-1 rounded-md hover:bg-blue-600 transition"
            >
              {{ showForm ? 'Cancel' : 'Add Note' }}
            </button>
          </div>

          <!-- Add Note Form -->
          <div v-if="showForm" class="mb-6">
            <textarea
                v-model="newNoteBody"
                rows="3"
                class="w-full border border-gray-300 rounded p-2"
                placeholder="Write your note here..."
            ></textarea>
            <button
                @click="submitNote"
                class="mt-2 bg-green-500 text-white text-xs px-3 py-1 rounded-md hover:bg-green-600 transition"
            >
              Submit Note
            </button>
          </div>

          <NoteList v-if="merchant.notes?.length" :notes="merchant.notes" />
          <p v-else class="text-gray-500">No notes available for this merchant.</p>
        </div>
      </transition>

    </div>
  </div>
</template>

<script>
import { useMerchantStore } from "../stores/merchantStore";
import NoteList from "../components/notes/NoteList.vue";
import MerchantItem from "../components/merchants/MerchantItem.vue";

export default {
  name: 'MerchantShow',
  components: {
    MerchantItem,
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
    merchant() {
      return useMerchantStore().merchant;
    }
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      async handler(newId) {
        const store = useMerchantStore();
        store.merchant = null;
        this.isLoading = true;
        await store.fetchMerchantProfile(newId);
        this.isLoading = false;
      }
    }
  },
  methods: {
    async submitNote() {
      const store = useMerchantStore();
      const trimmed = this.newNoteBody.trim();

      if (!trimmed) return;

      const newNote = {
        body: trimmed,
        merchant_id: this.merchant.id,
        user_id: 1 // Replace with actual auth user
      };

      await store.createNote(newNote);
      this.newNoteBody = '';
      this.showForm = false;
    }
  }
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
