@props([
    'variant' => 'default',
    'size' => 'default',
    'type' => 'button'
])

@php
    $variants = [
        'default' => 'bg-primary-light dark:bg-primary-dark text-white hover:opacity-90 shadow-sm hover:shadow-md transition-all',
        'secondary' => 'bg-white dark:bg-surface-dark border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 shadow-sm hover:shadow-md transition-all',
        'outline' => 'bg-transparent border border-primary-light/20 dark:border-primary-dark/20 text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-all',
        'ghost' => 'bg-transparent text-text-light dark:text-text-dark hover:bg-primary-light/10 dark:hover:bg-primary-dark/10 transition-all',
        'destructive' => 'bg-red-500 dark:bg-red-600 text-white hover:opacity-90 shadow-sm hover:shadow-md transition-all',
    ];
    
    $sizes = [
        'default' => 'h-10 px-4 sm:px-5 py-2 text-sm sm:text-base',
        'sm' => 'h-10 rounded-md px-3 sm:px-4 text-sm',
        'lg' => 'h-10 rounded-lg px-6 sm:px-8 text-base sm:text-lg',
        'icon' => 'h-10 w-10',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-lg font-medium focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-light dark:focus-visible:ring-primary-dark disabled:pointer-events-none disabled:opacity-50 {$variants[$variant]} {$sizes[$size]}"]) }}>
    {{ $slot }}
</button>

