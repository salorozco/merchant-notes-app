<template>
    <h2 class="text-xl font-bold truncate">{{ merchant.name }}</h2>
    <div class="text-sm text-gray-600">
      <p>Created: {{ merchant.created_at }}</p>
    </div>
</template>
<script>
export default {
  name: 'MerchantItem',
  props: {
    merchant: {
      type: Object,
      required: true
    }
  }
};
</script>

<style scoped>
</style>
