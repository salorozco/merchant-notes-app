<template>
  <div>
    <transition name="fade">
      <div  class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 justify-items-center"
            v-if="!isLoading && merchants"
          >
        <MerchantList
            v-if="merchants.length"
            :merchants="merchants"
        />
      </div>
    </transition>
  </div>
</template>


<script>
import { mapState, mapActions } from 'pinia';
import { useMerchantStore} from "../stores/merchantStore.js";
import MerchantList from "../components/merchants/MerchantList.vue";

export default {
  name: 'MerchantsView',
  components: { MerchantList },
  computed: {
    ...mapState(useMerchantStore, ['merchants', 'isLoading', 'error']),
  },
  methods: {
    ...mapActions(useMerchantStore, ['fetchMerchants']),
  },
  created() {
    const store = useMerchantStore();
    if (!store.isDataFetched || !store.merchants.length) {
      store.merchants = []; // Reset for transition
      this.fetchMerchants();
    }
  },
};
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 1s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>