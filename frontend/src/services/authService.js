// src/services/authService.js
import apiClient from './apiClient';

export default {
    async login(email, password) {
        await apiClient.get('/sanctum/csrf-cookie'); // ✅ use apiClient here

        return apiClient.post('/login', { email, password }); // 👈 keep /api prefix
    },

    logout() {
        return apiClient.post('/logout');
    },

    getCurrentUser() {
        return apiClient.get('/user');
    },
};
