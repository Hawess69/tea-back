<nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md shadow-sm border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 z-50" x-data="{ 
    isAuthenticated: false, 
    user: null, 
    mobileMenuOpen: false,
    init() {
        // Initialize auth state if available
        if (window.auth) {
            this.isAuthenticated = window.auth.isLoggedIn();
            this.user = window.auth.getUser();
        }
    }
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex items-center group">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center shadow-glow group-hover:scale-110 transition-transform duration-200">
                            <span class="text-white font-bold text-lg">T</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <h1 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors">Tea</h1>
                    </div>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors hover:bg-gray-50 dark:hover:bg-gray-700">Home</a>
                <a href="{{ url('/#features') }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors hover:bg-gray-50 dark:hover:bg-gray-700">Features</a>
                <a href="{{ url('/#about') }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors hover:bg-gray-50 dark:hover:bg-gray-700">About</a>
                
                <!-- Authentication Links -->
                <template x-if="!isAuthenticated">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors hover:bg-gray-50 dark:hover:bg-gray-700">Login</a>
                        <x-button variant="gradient" size="sm" href="{{ route('register') }}">
                            Get Started
                        </x-button>
                    </div>
                </template>
                
                <template x-if="isAuthenticated">
                    <div class="flex items-center space-x-4">
                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white" x-text="user?.name?.charAt(0) || 'U'"></span>
                                </div>
                                <span class="ml-2 text-gray-700 dark:text-gray-300" x-text="user?.name || 'User'"></span>
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Admin</a>
                                <button @click="window.auth ? window.auth.logout() : window.location.href='/logout'" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Sign out</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white p-2">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" 
             class="md:hidden border-t border-gray-200 dark:border-gray-700" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ url('/') }}" class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Home</a>
                <a href="{{ url('/#features') }}" class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Features</a>
                <a href="{{ url('/#about') }}" class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">About</a>
                
                <!-- Mobile Authentication Links -->
                <template x-if="!isAuthenticated">
                    <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center px-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700">?</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800 dark:text-gray-200">Guest</div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Not signed in</div>
                            </div>
                        </div>
                        <div class="mt-3 px-2 space-y-1">
                            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Login</a>
                            <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-indigo-600 text-white hover:bg-indigo-700">Get Started</a>
                        </div>
                    </div>
                </template>
                
                <template x-if="isAuthenticated">
                    <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center px-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white" x-text="user?.name?.charAt(0) || 'U'"></span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800 dark:text-gray-200" x-text="user?.name || 'User'"></div>
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400" x-text="user?.email || 'user@example.com'"></div>
                            </div>
                        </div>
                        <div class="mt-3 px-2 space-y-1">
                            <a href="{{ route('profile') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Profile</a>
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Admin</a>
                            <button @click="window.auth ? window.auth.logout() : window.location.href='/logout'" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Sign out</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</nav>
