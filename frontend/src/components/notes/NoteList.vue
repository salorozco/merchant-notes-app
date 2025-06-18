<template>
  <div class="mt-6">
    <NoteItem
        v-for="note in notes"
        :key="note.id"
        :note="note"
        @update-note="handleUpdate"
        @delete-note="handleDelete"
    />
  </div>
</template>


<script>
import NoteItem from './NoteItem.vue';
import { useMerchantStore } from "../../stores/merchantStore.js";

export default {
  name: 'NoteList',
  components: { NoteItem },
  props: {
    notes: Array
  },
  methods: {
    async handleUpdate(updatedNote) {
      const store = useMerchantStore();
      await store.updateNote(updatedNote);
    },
    async handleDelete(noteId) {
      const store = useMerchantStore();
      await store.deleteNote(noteId);
    },
  }
};
</script>

