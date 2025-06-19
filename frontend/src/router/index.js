import { createRouter, createWebHistory } from "vue-router";
import { useAuthStore } from "../stores/authStore.js";

import MerchantsView from "../views/MerchantsView.vue";
import MerchantShow from "../views/MerchantShow.vue";
import LoginView from "../views/LoginView.vue";
import ProfileView from "../views/ProfileView.vue";

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
        },
        {
            path: "/login",
            name: "Login",
            component: LoginView
        },
        {
            path: "/profile",
            name: "Profile",
            component: ProfileView,
            meta: { requiresAuth: true } // 🔐 Only logged-in users
        },
    ]
});

router.beforeEach(async (to, from, next) => {
    const auth = useAuthStore();

    if (to.meta.requiresAuth && !auth.user) {
        try {
            await auth.fetchUser();
        } catch {
            return next('/login');
        }
    }

    if (to.meta.requiresAuth && !auth.user) {
        return next('/login');
    }

    if (to.path === '/login' && auth.user) {
        return next('/');
    }

    return next();
});

export default router
