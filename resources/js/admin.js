// Admin Alpine.js Components
document.addEventListener('alpine:init', () => {
    // Admin Actions Component
    Alpine.data('adminActions', () => ({
        loading: false,
        
        async toggleBan(userId, currentStatus) {
            this.loading = true;
            
            try {
                const response = await fetch(`/api/v1/admin/users/${userId}/ban`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    // Reload the page to update the UI
                    window.location.reload();
                } else {
                    this.showNotification('Failed to update user status', 'error');
                }
            } catch (error) {
                this.showNotification('An error occurred', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        async changeRole(userId, newRole) {
            this.loading = true;
            
            try {
                const response = await fetch(`/api/v1/admin/users/${userId}/role`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ role: newRole })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    window.location.reload();
                } else {
                    this.showNotification('Failed to update user role', 'error');
                }
            } catch (error) {
                this.showNotification('An error occurred', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        async toggleHide(postId, postType, currentStatus) {
            this.loading = true;
            
            try {
                const response = await fetch(`/api/v1/admin/posts/${postType}/${postId}/hide`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    window.location.reload();
                } else {
                    this.showNotification('Failed to update post status', 'error');
                }
            } catch (error) {
                this.showNotification('An error occurred', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        async publishPost(postId, postType) {
            this.loading = true;
            
            try {
                const response = await fetch(`/api/v1/admin/posts/${postType}/${postId}/publish`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    window.location.reload();
                } else {
                    this.showNotification('Failed to publish post', 'error');
                }
            } catch (error) {
                this.showNotification('An error occurred', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
    }));
    
    // Confirmation Modal Component
    Alpine.data('confirmDelete', () => ({
        showModal: false,
        itemId: null,
        itemType: '',
        
        open(itemId, itemType = 'item') {
            this.itemId = itemId;
            this.itemType = itemType;
            this.showModal = true;
        },
        
        close() {
            this.showModal = false;
            this.itemId = null;
            this.itemType = '';
        },
        
        async confirm() {
            if (this.itemId) {
                // Create a form and submit it for deletion
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = window.location.href + '/' + this.itemId;
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                
                const tokenField = document.createElement('input');
                tokenField.type = 'hidden';
                tokenField.name = '_token';
                tokenField.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                form.appendChild(methodField);
                form.appendChild(tokenField);
                document.body.appendChild(form);
                form.submit();
            }
            this.close();
        }
    }));
    
    // Search and Filter Component
    Alpine.data('searchFilter', () => ({
        search: '',
        filters: {},
        
        init() {
            // Initialize from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            this.search = urlParams.get('search') || '';
            
            // Initialize filters from form data
            const form = this.$el;
            if (form) {
                const formData = new FormData(form);
                for (let [key, value] of formData.entries()) {
                    if (key !== 'search' && value) {
                        this.filters[key] = value;
                    }
                }
            }
        },
        
        applyFilters() {
            const params = new URLSearchParams();
            
            if (this.search) {
                params.set('search', this.search);
            }
            
            Object.entries(this.filters).forEach(([key, value]) => {
                if (value) {
                    params.set(key, value);
                }
            });
            
            const queryString = params.toString();
            window.location.href = window.location.pathname + (queryString ? '?' + queryString : '');
        },
        
        clearFilters() {
            this.search = '';
            this.filters = {};
            window.location.href = window.location.pathname;
        }
    }));
});

// Global functions for backward compatibility
function toggleBan(userId, currentStatus) {
    Alpine.store('adminActions').toggleBan(userId, currentStatus);
}

function confirmDelete(itemId, itemType = 'item') {
    Alpine.store('confirmDelete').open(itemId, itemType);
}

function changeRole(userId, newRole) {
    Alpine.store('adminActions').changeRole(userId, newRole);
}

function toggleHide(postId, postType, currentStatus) {
    Alpine.store('adminActions').toggleHide(postId, postType, currentStatus);
}

function publishPost(postId, postType) {
    Alpine.store('adminActions').publishPost(postId, postType);
}
