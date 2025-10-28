/**
 * Authentication Helper Functions
 * Manages authentication state and user session
 */

import { authAPI } from './api.js';

class AuthManager {
    constructor() {
        this.user = null;
        this.token = localStorage.getItem('auth_token');
        this.isAuthenticated = !!this.token;
    }

    /**
     * Check if user is authenticated
     */
    isLoggedIn() {
        return this.isAuthenticated && this.token;
    }

    /**
     * Get current user data
     */
    getUser() {
        return this.user;
    }

    /**
     * Set user data
     */
    setUser(user) {
        this.user = user;
    }

    /**
     * Login user
     */
    async login(credentials) {
        try {
            const response = await authAPI.login(credentials);
            this.user = response.user;
            this.token = response.token;
            this.isAuthenticated = true;
            
            // Store in localStorage
            localStorage.setItem('auth_token', this.token);
            localStorage.setItem('user', JSON.stringify(this.user));
            
            return response;
        } catch (error) {
            throw error;
        }
    }

    /**
     * Register new user
     */
    async register(userData) {
        try {
            const response = await authAPI.register(userData);
            this.user = response.user;
            this.token = response.token;
            this.isAuthenticated = true;
            
            // Store in localStorage
            localStorage.setItem('auth_token', this.token);
            localStorage.setItem('user', JSON.stringify(this.user));
            
            return response;
        } catch (error) {
            throw error;
        }
    }

    /**
     * Logout user
     */
    async logout() {
        try {
            await authAPI.logout();
        } catch (error) {
            console.error('Logout error:', error);
        } finally {
            // Clear local state
            this.user = null;
            this.token = null;
            this.isAuthenticated = false;
            
            // Clear localStorage
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            
            // Redirect to home
            window.location.href = '/';
        }
    }

    /**
     * Get user profile
     */
    async getProfile() {
        try {
            const response = await authAPI.getProfile();
            this.user = response.user;
            localStorage.setItem('user', JSON.stringify(this.user));
            return response;
        } catch (error) {
            // If profile fetch fails, user might be logged out
            if (error.message.includes('Unauthenticated')) {
                this.logout();
            }
            throw error;
        }
    }

    /**
     * Update user profile
     */
    async updateProfile(data) {
        try {
            const response = await authAPI.updateProfile(data);
            this.user = response.user;
            localStorage.setItem('user', JSON.stringify(this.user));
            return response;
        } catch (error) {
            throw error;
        }
    }

    /**
     * Initialize auth state from localStorage
     */
    init() {
        const storedUser = localStorage.getItem('user');
        if (storedUser && this.token) {
            try {
                this.user = JSON.parse(storedUser);
                this.isAuthenticated = true;
            } catch (error) {
                console.error('Error parsing stored user:', error);
                this.logout();
            }
        }
    }

    /**
     * Check if user has specific role
     */
    hasRole(role) {
        return this.user && this.user.role === role;
    }

    /**
     * Check if user is admin
     */
    isAdmin() {
        return this.hasRole('admin');
    }

    /**
     * Check if user is moderator
     */
    isModerator() {
        return this.hasRole('moderator') || this.isAdmin();
    }
}

// Create global auth instance
window.auth = new AuthManager();

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    window.auth.init();
});

// Alpine.js data for authentication
window.authData = () => ({
    user: null,
    isAuthenticated: false,
    
    init() {
        this.user = window.auth.getUser();
        this.isAuthenticated = window.auth.isLoggedIn();
        
        // Listen for auth changes
        window.addEventListener('auth:login', () => {
            this.user = window.auth.getUser();
            this.isAuthenticated = true;
        });
        
        window.addEventListener('auth:logout', () => {
            this.user = null;
            this.isAuthenticated = false;
        });
    },
    
    async login(credentials) {
        try {
            const response = await window.auth.login(credentials);
            this.user = response.user;
            this.isAuthenticated = true;
            window.dispatchEvent(new CustomEvent('auth:login'));
            return response;
        } catch (error) {
            throw error;
        }
    },
    
    async register(userData) {
        try {
            const response = await window.auth.register(userData);
            this.user = response.user;
            this.isAuthenticated = true;
            window.dispatchEvent(new CustomEvent('auth:login'));
            return response;
        } catch (error) {
            throw error;
        }
    },
    
    async logout() {
        await window.auth.logout();
        this.user = null;
        this.isAuthenticated = false;
        window.dispatchEvent(new CustomEvent('auth:logout'));
    }
});

export default AuthManager;
