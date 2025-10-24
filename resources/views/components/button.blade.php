@props([
    'variant' => 'primary',
    'size' => 'md',
    'loading' => false,
    'disabled' => false,
    'icon' => null,
    'href' => null
])

@php
$baseClasses = 'inline-flex items-center justify-center font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105';
$variantClasses = [
    'primary' => 'btn-primary',
    'secondary' => 'btn-secondary',
    'danger' => 'bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white shadow-lg hover:shadow-xl focus:ring-red-500',
    'success' => 'bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white shadow-lg hover:shadow-xl focus:ring-green-500',
    'outline' => 'border-2 border-primary-600 text-primary-600 hover:bg-primary-600 hover:text-white bg-transparent shadow-lg hover:shadow-xl focus:ring-primary-500',
    'ghost' => 'text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 focus:ring-primary-500',
    'gradient' => 'bg-gradient-to-r from-primary-500 via-secondary-500 to-accent-500 hover:from-primary-600 hover:via-secondary-600 hover:to-accent-600 text-white shadow-glow hover:shadow-glow-lg focus:ring-primary-500'
];
$sizeClasses = [
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2.5 text-sm',
    'lg' => 'px-6 py-3 text-base',
    'xl' => 'px-8 py-4 text-lg'
];

$tag = $href ? 'a' : 'button';
$attributes = $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size]]);

if ($href) {
    $attributes = $attributes->merge(['href' => $href]);
}
@endphp

<{{ $tag }} 
    {{ $attributes }}
    @if($loading || $disabled) disabled @endif
    x-data="{ loading: @js($loading) }"
    x-bind:disabled="loading"
>
    <template x-if="loading">
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </template>
    
    <template x-if="!loading && @js($icon)">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    </template>
    
    <template x-if="!loading && !@js($icon)">
        <span>{{ $slot }}</span>
    </template>
    
    <template x-if="loading">
        <span>Loading...</span>
    </template>
</{{ $tag }}>
