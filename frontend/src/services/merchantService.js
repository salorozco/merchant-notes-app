import apiClient from './apiClient';

export default {
    getMerchants() {
        return apiClient.get('/api/merchants');
    },
    getMerchantById(id) {
        return apiClient.get(`/api/merchants/${id}`);
    },
    getNotesByMerchantId(id) {
        return apiClient.get(`/api/merchants/${id}/notes`);
    },
    addNoteToMerchant(merchantId, noteData) {
        return apiClient.post(`/api/merchants/${merchantId}/notes`, noteData);
    },
    updateNote(merchantId, noteId, noteData) {
        return apiClient.put(`/api/merchants/${merchantId}/notes/${noteId}`, noteData);
    },
    deleteNote(merchantId, noteId) {
        return apiClient.delete(`/api/merchants/${merchantId}/notes/${noteId}`);
    }
};
