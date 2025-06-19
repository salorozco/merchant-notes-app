import axios from 'axios';

const apiClient = axios.create({
    baseURL: 'http://localhost',
    withCredentials: true,
    withXSRFToken: true,
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    },
});

apiClient.interceptors.request.use((config) => {
    return config;
});

export default apiClient;
