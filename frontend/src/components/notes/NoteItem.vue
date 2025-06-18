
<template>
  <div class="p-4 bg-gray-100 rounded mb-4 shadow-sm">
    <div v-if="!isEditing">
      <p class="text-gray-700">{{ note.body }}</p>
      <p class="text-sm text-gray-500 mt-2">Created: {{ note.created_at }}</p>

      <div class="mt-4 flex items-center">
        <button
            @click="startEdit"
            class="bg-blue-500 text-white text-xs px-2 py-1 rounded-md hover:bg-blue-600 transition mr-2"
           >
          Edit
        </button>
        <button
            @click="deleteNote"
            class="bg-red-500 text-white text-xs px-2 py-1 rounded-md hover:bg-red-600 transition"
           >
          Delete
        </button>
      </div>
    </div>

    <div v-else>
      <textarea
          v-model="editedBody"
          class="w-full p-2 border border-gray-300 rounded"
          rows="3"
      />
      <div class="mt-4 flex items-center">
        <button
            @click="saveEdit"
            class="bg-green-500 text-white text-xs px-2 py-1 rounded-md hover:bg-green-600 transition "
        >
          Save
        </button>
        <button
            @click="cancelEdit"
            class="ml-2 bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-md hover:bg-red-300 transition"
        >
          Cancel
        </button>
      </div>


    </div>
  </div>
</template>

<script>
export default {
  name: 'NoteItem',
  props: {
    note: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      isEditing: false,
      editedBody: this.note.body
    };
  },
  methods: {
    startEdit() {
      this.editedBody = this.note.body;
      this.isEditing = true;
    },
    cancelEdit() {
      this.isEditing = false;
    },
    async saveEdit() {
      this.$emit('update-note', {
        ...this.note,
        body: this.editedBody
      });
      this.isEditing = false;
    },
    deleteNote() {
      if (confirm('Are you sure you want to delete this note?')) {
        this.$emit('delete-note', this.note.id);
      }
    }
  }
};
</script>
