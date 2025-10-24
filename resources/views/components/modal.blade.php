@props([
    'id' => 'modal',
    'size' => 'md',
    'closable' => true
])

@php
$sizeClasses = [
    'sm' => 'max-w-md',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
    'full' => 'max-w-full mx-4'
];
@endphp

<div 
    x-data="{ open: false }" 
    x-show="open" 
    @open-modal.window="if ($event.detail.id === '{{ $id }}') open = true"
    @close-modal.window="if ($event.detail.id === '{{ $id }}') open = false"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>
    
    <!-- Modal -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div 
            class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 shadow-xl transition-all {{ $sizeClasses[$size] }} w-full"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <!-- Header -->
            @if(isset($header) || $closable)
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    @if(isset($header))
                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $header }}
                        </div>
                    @else
                        <div></div>
                    @endif
                    
                    @if($closable)
                        <button 
                            @click="open = false"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md p-1"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            @endif
            
            <!-- Body -->
            <div class="p-6">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            @if(isset($footer))
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
