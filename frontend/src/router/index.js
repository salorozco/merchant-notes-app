import { createRouter, createWebHistory } from "vue-router";
import MerchantsView from "../views/MerchantsView.vue";
import MerchantShow from "../views/MerchantShow.vue";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "Home",
            component: MerchantsView
        },
        {
            path: "/merchants/:id",
            name: "MerchantShow",
            component: MerchantShow
        }
    ]
})

export default router;