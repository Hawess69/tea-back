@props([
    'variant' => 'default',
    'padding' => 'md',
    'shadow' => 'sm'
])

@php
$baseClasses = 'bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700';
$variantClasses = [
    'default' => '',
    'elevated' => 'shadow-lg',
    'outlined' => 'border-2',
    'flat' => 'shadow-none border-0'
];
$paddingClasses = [
    'none' => '',
    'sm' => 'p-4',
    'md' => 'p-6',
    'lg' => 'p-8',
    'xl' => 'p-10'
];
$shadowClasses = [
    'none' => 'shadow-none',
    'sm' => 'shadow-sm',
    'md' => 'shadow-md',
    'lg' => 'shadow-lg',
    'xl' => 'shadow-xl'
];
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $paddingClasses[$padding] . ' ' . $shadowClasses[$shadow]]) }}>
    @if(isset($header))
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
            {{ $header }}
        </div>
    @endif
    
    {{ $slot }}
    
    @if(isset($footer))
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            {{ $footer }}
        </div>
    @endif
</div>
