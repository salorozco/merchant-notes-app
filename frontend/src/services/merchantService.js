// src/services/merchantService.js

import apiClient from './apiClient';

export default {
    getMerchants() {
        return apiClient.get('/merchants');
    },
    getMerchantById(id) {
        return apiClient.get(`/merchants/${id}`);
    },
    getNotesByMerchantId(id) {
        return apiClient.get(`/merchants/${id}/notes`);
    },
    addNoteToMerchant(merchantId, noteData) {
        return apiClient.post(`/merchants/${merchantId}/notes`, noteData);
    },
    updateNote(merchantId, noteId, noteData) {
        return apiClient.put(`/merchants/${merchantId}/notes/${noteId}`, noteData);
    },
    deleteNote(merchantId, noteId) {
        console.log(noteId)
        return apiClient.delete(`/merchants/${merchantId}/notes/${noteId}`);
    }
};
