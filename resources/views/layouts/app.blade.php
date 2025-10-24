<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" 
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Tea - Connect & Share')</title>
    <meta name="description" content="@yield('description', 'Tea is a social platform for connecting and sharing experiences. Join our community today!')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Head Content -->
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <!-- Navigation -->
    @include('components.nav')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('components.footer')
    
    <!-- Notifications -->
    <div id="notifications" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
    <!-- Additional Scripts -->
    @stack('scripts')
    
    <!-- Alpine.js Data -->
    <script>
        // Global Alpine.js data
        document.addEventListener('alpine:init', () => {
            Alpine.data('notifications', () => ({
                items: [],
                add(message, type = 'info') {
                    const id = Date.now();
                    this.items.push({ id, message, type });
                    setTimeout(() => this.remove(id), 5000);
                },
                remove(id) {
                    this.items = this.items.filter(item => item.id !== id);
                }
            }));
        });
    </script>
</body>
</html>
