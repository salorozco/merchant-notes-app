import { defineStore } from 'pinia';
import MerchantService from "../services/merchantService.js";

export const useMerchantStore = defineStore('merchants', {
    state: () => ({
        merchants: [],
        merchant: null,
        isLoading: false,
        error: null,
        isDataFetched: false,
    }),

    getters: {
        getMerchantById: (state) => {
            return (id) => {
                const normalizedId = Number(id);
                return state.merchants.find(merchant => merchant.id === normalizedId);
            };
        },
    },

    actions: {
        async fetchMerchants() {
            this.isLoading = true;
            this.error = null;
            try {
                const response = await MerchantService.getMerchants();
                this.merchants = response.data.data;
                this.isDataFetched = true;
            } catch (error) {
                this.error = 'Error fetching merchants';
                console.error('Error fetching merchants:', error);
            } finally {
                this.isLoading = false;
            }
        },

        async fetchMerchantById(id) {
            this.isLoading = true;
            this.error = null;

            const merchant = this.getMerchantById(id);
            if (merchant) {
                this.merchant = merchant;
                this.isLoading = false;
            } else {
                try {
                    const response = await MerchantService.getMerchantById(id);
                    this.merchant = response.data.data;
                    this.merchants.push(this.merchant);
                } catch (error) {
                    this.error = `Error fetching merchant with ID ${id}`;
                    console.error(`Error fetching merchant with ID ${id}:`, error);
                } finally {
                    this.isLoading = false;
                }
            }
        },

        async fetchMerchantProfile(id) {
            this.isLoading = true;
            this.error = null;

            const existing = this.merchant && this.merchant.id === Number(id) && this.merchant.notes;

            if (existing) {
                this.isLoading = false;
                return;
            }

            try {
                const [merchantRes, notesRes] = await Promise.all([
                    MerchantService.getMerchantById(id),
                    MerchantService.getNotesByMerchantId(id)
                ]);

                this.merchant = {
                    ...merchantRes.data.data,
                    notes: notesRes.data.data
                };
            } catch (error) {
                this.error = `Error fetching merchant profile for ID ${id}`;
                console.error(this.error, error);
            } finally {
                this.isLoading = false;
            }
        },

        async updateNote(updatedNote) {
            if (!this.merchant || !this.merchant.id) return;

            try {
                await MerchantService.updateNote(this.merchant.id, updatedNote.id, {
                    body: updatedNote.body
                });

                const index = this.merchant.notes.findIndex(note => note.id === updatedNote.id);
                if (index !== -1) {
                    this.merchant.notes[index] = { ...this.merchant.notes[index], ...updatedNote };
                }
            } catch (error) {
                console.error('Failed to update note:', error);
                this.error = 'Failed to update note';
            }
        },

        async deleteNote(noteId) {
            try {
                await MerchantService.deleteNote(this.merchant.id, noteId);
                this.merchant.notes = this.merchant.notes.filter(n => n.id !== noteId);
            } catch (err) {
                console.error('Error deleting note:', err);
            }
        },

        async createNote(noteData) {
            try {
                const res = await MerchantService.addNoteToMerchant(noteData.merchant_id, noteData);
                this.merchant.notes.unshift(res.data.data);
            } catch (error) {
                console.error('Error creating note:', error);
            }
        }
    },
    persist: {
        key: 'merchants-store',
        paths: ['merchants', 'merchant',  'isDataFetched']
    }
});
