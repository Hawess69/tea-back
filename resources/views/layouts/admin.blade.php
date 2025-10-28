<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false }" 
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard - Tea')</title>
    <meta name="description" content="Tea Admin Dashboard - Manage users, posts, and platform analytics">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Admin JavaScript -->
    <script>
        // Admin Alpine.js Components
        document.addEventListener('alpine:init', () => {
            // Admin Actions Component
            Alpine.store('adminActions', {
                loading: false,
                
                async toggleBan(userId, currentStatus) {
                    this.loading = true;
                    
                    try {
                        const response = await fetch(`/admin/users/${userId}/ban`, {
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
                        const response = await fetch(`/admin/users/${userId}/role`, {
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
                        const response = await fetch(`/admin/${postType}-posts/${postId}/hide`, {
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
                        const response = await fetch(`/admin/${postType}-posts/${postId}/publish`, {
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
            });
            
            // Confirmation Modal Component
            Alpine.store('confirmDelete', {
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
                        // Determine the correct delete route based on current page
                        let deleteUrl;
                        const currentPath = window.location.pathname;
                        
                        if (currentPath.includes('/admin/feed-posts')) {
                            deleteUrl = `/admin/feed-posts/${this.itemId}`;
                        } else if (currentPath.includes('/admin/men-posts')) {
                            deleteUrl = `/admin/men-posts/${this.itemId}`;
                        } else if (currentPath.includes('/admin/users')) {
                            deleteUrl = `/admin/users/${this.itemId}`;
                        } else {
                            // Fallback: try to construct URL from current path
                            deleteUrl = `${currentPath}/${this.itemId}`;
                        }
                        
                        // Create a form and submit it for deletion
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = deleteUrl;
                        
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
            });
            
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
            // Prevent any default behavior
            event.preventDefault();
            event.stopPropagation();
            
            // Show the modal using a simpler approach
            const modal = document.getElementById('confirmDeleteModal');
            const modalItemId = document.getElementById('modalItemId');
            const modalItemType = document.getElementById('modalItemType');
            const modalItemType2 = document.getElementById('modalItemType2');
            
            if (modal && modalItemId && modalItemType && modalItemType2) {
                modalItemId.value = itemId;
                modalItemType.textContent = itemType;
                modalItemType2.textContent = itemType;
                modal.style.display = 'block';
                modal.classList.add('show');
            }
        }
        
        function closeModal() {
            const modal = document.getElementById('confirmDeleteModal');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
            }
        }
        
        // Close modal when clicking outside of it
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('confirmDeleteModal');
            if (modal && modal.style.display === 'block' && event.target === modal) {
                closeModal();
            }
        });
        
        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
        
        function confirmDeleteAction() {
            const modal = document.getElementById('confirmDeleteModal');
            const modalItemId = document.getElementById('modalItemId');
            
            if (modal && modalItemId && modalItemId.value) {
                const itemId = modalItemId.value;
                
                // Determine the correct delete route based on current page
                let deleteUrl;
                const currentPath = window.location.pathname;
                
                if (currentPath.includes('/admin/feed-posts')) {
                    deleteUrl = `/admin/feed-posts/${itemId}`;
                } else if (currentPath.includes('/admin/men-posts')) {
                    deleteUrl = `/admin/men-posts/${itemId}`;
                } else if (currentPath.includes('/admin/users')) {
                    deleteUrl = `/admin/users/${itemId}`;
                } else {
                    // Fallback: try to construct URL from current path
                    deleteUrl = `${currentPath}/${itemId}`;
                }
                
                // Create a form and submit it for deletion
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                
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
            
            closeModal();
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
    </script>
    
    <!-- Additional Head Content -->
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0"
         :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
         x-show="true">
        
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 px-4 bg-indigo-600 dark:bg-indigo-700">
            <h1 class="text-xl font-bold text-white">Tea Admin</h1>
        </div>
        
        <!-- Navigation -->
        <nav class="mt-5 px-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                </svg>
                Dashboard
            </a>
            
            <a href="{{ route('admin.users.index') }}" 
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                Users
            </a>
            
            <a href="{{ route('admin.feed-posts.index') }}" 
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.feed-posts*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Feed Posts
            </a>
            
            <a href="{{ route('admin.men-posts.index') }}" 
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.men-posts*') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                Men Posts
            </a>
            
            <a href="{{ route('admin.analytics') }}" 
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.analytics') ? 'bg-indigo-100 text-indigo-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Analytics
            </a>
        </nav>
    </div>
    
    <!-- Main Content -->
    <div class="lg:pl-64">
        <!-- Top Navigation -->
        <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
            <!-- Mobile menu button -->
            <button type="button" class="-m-2.5 p-2.5 text-gray-700 dark:text-gray-300 lg:hidden" 
                    @click="sidebarOpen = !sidebarOpen">
                <span class="sr-only">Open sidebar</span>
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            
            <!-- Page title -->
            <div class="flex-1">
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white">@yield('page-title', 'Dashboard')</h1>
            </div>
            
            <!-- Right side -->
            <div class="flex items-center gap-x-4">
                <!-- Dark mode toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                        class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg x-show="!darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                    <svg x-show="darkMode" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </button>
                
                <!-- Notifications -->
                <button class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <span class="sr-only">View notifications</span>
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z"></path>
                    </svg>
                </button>
                
                <!-- Profile dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-x-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center">
                            <span class="text-sm font-medium text-white">A</span>
                        </div>
                        <span class="hidden lg:block">Admin User</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="open" @click.away="open = false" 
                         class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white dark:bg-gray-800 py-1 shadow-lg ring-1 ring-black ring-opacity-5">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Your Profile</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Settings</a>
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page Content -->
        <main class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>
    
    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"></div>
    
    <!-- Notifications -->
    <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <!-- Confirmation Modal -->
    <div id="confirmDeleteModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Delete <span id="modalItemType">item</span></h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this <span id="modalItemType2">item</span>? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="confirmDeleteAction()" class="btn btn-danger">Delete</button>
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </div>
    
    <!-- Hidden input to store item ID -->
    <input type="hidden" id="modalItemId" value="">
    
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .modal.show {
            opacity: 1;
        }
        
        .modal-content {
            background: white;
            border-radius: 8px;
            padding: 0;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }
        
        .modal.show .modal-content {
            transform: scale(1);
        }
        
        .modal-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .modal-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: #111827;
        }
        
        .modal-body {
            padding: 16px 24px;
        }
        
        .modal-body p {
            margin: 0;
            color: #6b7280;
        }
        
        .modal-footer {
            padding: 16px 24px 20px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }
        
        .btn-danger {
            background-color: #dc2626;
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #b91c1c;
        }
        
        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .modal-content {
                background: #1f2937;
            }
            
            .modal-header {
                border-bottom-color: #374151;
            }
            
            .modal-header h3 {
                color: #f9fafb;
            }
            
            .modal-body p {
                color: #d1d5db;
            }
        }
    </style>
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
