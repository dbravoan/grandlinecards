import axios from 'axios';

// For Android Emulator use 'http://10.0.2.2:80/api/v1'
// For Browser/iOS Simulator use 'http://localhost/api/v1' (if mapped)
const API_URL = 'http://localhost/api/v1';

const api = axios.create({
    baseURL: API_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    timeout: 10000,
});

export const cardService = {
    async getCards(params = {}) {
        const response = await api.get('/cards', { params });
        return response.data; // Expecting { data: [...], meta: ... } from Resource collection
    },
    async getCardDetail(id) {
        const response = await api.get(`/cards/${id}`);
        return response.data;
    }
};

export default api;
