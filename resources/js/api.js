/**
 * API Client for Tea Backend
 * Centralized API communication with authentication and error handling
 */

class ApiClient {
    constructor() {
        this.baseURL = '/api/v1';
        this.token = localStorage.getItem('auth_token');
    }

    /**
     * Set authentication token
     */
    setToken(token) {
        this.token = token;
        if (token) {
            localStorage.setItem('auth_token', token);
        } else {
            localStorage.removeItem('auth_token');
        }
    }

    /**
     * Get CSRF token from meta tag
     */
    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    /**
     * Make HTTP request
     */
    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        const config = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.getCsrfToken(),
                ...options.headers
            },
            ...options
        };

        // Add auth token if available
        if (this.token) {
            config.headers.Authorization = `Bearer ${this.token}`;
        }

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Request failed');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    /**
     * GET request
     */
    async get(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = queryString ? `${endpoint}?${queryString}` : endpoint;
        return this.request(url, { method: 'GET' });
    }

    /**
     * POST request
     */
    async post(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    /**
     * PUT request
     */
    async put(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    /**
     * DELETE request
     */
    async delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }
}

// Authentication API methods
export const authAPI = {
    async login(credentials) {
        const api = new ApiClient();
        const response = await api.post('/auth/login', credentials);
        api.setToken(response.token);
        return response;
    },

    async register(userData) {
        const api = new ApiClient();
        const response = await api.post('/auth/register', userData);
        api.setToken(response.token);
        return response;
    },

    async logout() {
        const api = new ApiClient();
        await api.post('/auth/logout');
        api.setToken(null);
    },

    async getProfile() {
        const api = new ApiClient();
        return api.get('/profile');
    },

    async updateProfile(data) {
        const api = new ApiClient();
        return api.put('/profile', data);
    }
};

// Feed Posts API methods
export const feedAPI = {
    async getPosts(params = {}) {
        const api = new ApiClient();
        return api.get('/feed/posts', params);
    },

    async createPost(data) {
        const api = new ApiClient();
        return api.post('/feed/posts', data);
    },

    async getPost(id) {
        const api = new ApiClient();
        return api.get(`/feed/posts/${id}`);
    },

    async votePost(id, voteType) {
        const api = new ApiClient();
        return api.post(`/feed/posts/${id}/vote`, { vote_type: voteType });
    },

    async getComments(postId, params = {}) {
        const api = new ApiClient();
        return api.get(`/feed/posts/${postId}/comments`, params);
    },

    async addComment(postId, data) {
        const api = new ApiClient();
        return api.post(`/feed/posts/${postId}/comments`, data);
    }
};

// Men Posts API methods
export const menAPI = {
    async getPosts(params = {}) {
        const api = new ApiClient();
        return api.get('/men/posts', params);
    },

    async createPost(data) {
        const api = new ApiClient();
        return api.post('/men/posts', data);
    },

    async getPost(id) {
        const api = new ApiClient();
        return api.get(`/men/posts/${id}`);
    },

    async flagPost(id, flagType) {
        const api = new ApiClient();
        return api.post(`/men/posts/${id}/flag`, { flag_type: flagType });
    },

    async getComments(postId, params = {}) {
        const api = new ApiClient();
        return api.get(`/men/posts/${postId}/comments`, params);
    },

    async addComment(postId, data) {
        const api = new ApiClient();
        return api.post(`/men/posts/${postId}/comments`, data);
    }
};

// Alerts API methods
export const alertAPI = {
    async getAlerts(params = {}) {
        const api = new ApiClient();
        return api.get('/alerts', params);
    },

    async createAlert(data) {
        const api = new ApiClient();
        return api.post('/alerts', data);
    },

    async deleteAlert(id) {
        const api = new ApiClient();
        return api.delete(`/alerts/${id}`);
    }
};

// Events API methods
export const eventAPI = {
    async getEvents(params = {}) {
        const api = new ApiClient();
        return api.get('/events', params);
    }
};

// Create global API instance
window.api = new ApiClient();
