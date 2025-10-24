@props([
    'responsive' => true,
    'striped' => false,
    'hover' => true
])

@php
$tableClasses = 'min-w-full divide-y divide-gray-200 dark:divide-gray-700';
$theadClasses = 'bg-gray-50 dark:bg-gray-800';
$tbodyClasses = 'bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700';
$trClasses = $striped ? 'even:bg-gray-50 dark:even:bg-gray-800' : '';
$tdClasses = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100';
$thClasses = 'px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider';
@endphp

<div @if($responsive) class="overflow-x-auto" @endif>
    <table class="{{ $tableClasses }}">
        @if(isset($header))
            <thead class="{{ $theadClasses }}">
                <tr>
                    {{ $header }}
                </tr>
            </thead>
        @endif
        
        <tbody class="{{ $tbodyClasses }}">
            {{ $slot }}
        </tbody>
    </table>
    
    @if(isset($pagination))
        <div class="mt-4">
            {{ $pagination }}
        </div>
    @endif
</div>
