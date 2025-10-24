@props([
    'type' => 'text',
    'label' => null,
    'error' => null,
    'icon' => null,
    'required' => false,
    'placeholder' => null
])

<div class="space-y-2">
    @if($label)
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $icon !!}
                </svg>
            </div>
        @endif
        
        <input 
            {{ $attributes->merge([
                'type' => $type,
                'class' => 'form-input' . ($icon ? ' pl-10' : '') . ($error ? ' border-red-500 focus:ring-red-500 focus:border-red-500' : ''),
                'placeholder' => $placeholder
            ]) }}
        />
    </div>
    
    @if($error)
        <p class="text-sm text-red-600 dark:text-red-400 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ $error }}
        </p>
    @endif
</div>
