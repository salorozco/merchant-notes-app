<template>
    <RouterLink
        v-for="merchant in merchants"
        :key="merchant.id"
        :to="`/merchants/${merchant.id}`"
        class="block w-full h-40 bg-white rounded-lg shadow p-4 flex flex-col justify-between hover:shadow-lg transition"
        >
      <MerchantItem :merchant="merchant" />
    </RouterLink>
</template>

<script>
import MerchantItem from './MerchantItem.vue';

export default {
  name: 'MerchantList',
  props: {
    merchants: {
      type: Array,
      required: true
    }
  },
  components: {
    MerchantItem
  }
};
</script>

<style scoped>
.merchant-list {

  display: contents;
}
</style>
