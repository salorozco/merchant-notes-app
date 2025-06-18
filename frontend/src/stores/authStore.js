import { defineStore } from 'pinia';
import AuthService from '../services/authService.js';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        error: null,
        isLoading: false,
    }),

    actions: {
        async login(email, password) {
            this.isLoading = true;
            this.error = null;

            try {
                const res = await AuthService.login(email, password);
                this.user = res.data.user;
            } catch (error) {
                console.error('Login failed:', error);
                this.user = null;
                this.error = error.response?.data?.message || 'Login failed';
            } finally {
                this.isLoading = false;
            }
        },

        async fetchUser() {
            this.isLoading = true;
            try {
                const res = await AuthService.getCurrentUser();
                this.user = res.data;
            } catch (error) {
                this.user = null;
                console.warn('User not authenticated');
            } finally {
                this.isLoading = false;
            }
        },

        async logout() {
            this.isLoading = true;
            try {
                await AuthService.logout();
                this.user = null;
            } catch (error) {
                console.error('Logout failed:', error);
            } finally {
                this.isLoading = false;
            }
        },
    },

    persist: {
        key: 'auth-store',
        paths: ['user']
    }
});
