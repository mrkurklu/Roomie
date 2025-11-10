@props([
    'variant' => 'default',
    'size' => 'default',
    'type' => 'button'
])

@php
    $variants = [
        'default' => 'bg-transparent border-2 border-first-color text-first-color dark:border-blue-500 dark:text-blue-400 hover:bg-first-color/10 dark:hover:bg-blue-500/20 hover:border-first-color/80 dark:hover:border-blue-400 dark:hover:text-blue-300 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg',
        'secondary' => 'bg-transparent border-2 border-[#929aab] dark:border-slate-600 dark:text-slate-300 text-gray-700 hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg',
        'outline' => 'bg-transparent border-2 border-[#929aab] dark:border-slate-600 dark:text-slate-300 text-gray-700 hover:bg-[#929aab]/10 dark:hover:bg-slate-700/50 hover:border-[#929aab]/80 dark:hover:border-slate-500 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg',
        'ghost' => 'bg-transparent border-2 border-transparent text-gray-700 dark:text-slate-300 hover:bg-gray-100/50 dark:hover:bg-slate-700/50 hover:border-gray-300 dark:hover:border-slate-600',
            'destructive' => 'bg-transparent border-2 border-third-color dark:border-yellow-500 dark:text-yellow-400 text-third-color hover:bg-third-color/10 dark:hover:bg-yellow-500/20 hover:border-third-color/80 dark:hover:border-yellow-400 dark:hover:text-yellow-300 shadow-sm dark:shadow-md hover:shadow-md dark:hover:shadow-lg',
    ];
    
    $sizes = [
        'default' => 'h-10 px-4 sm:px-5 py-2 text-sm sm:text-base',
        'sm' => 'h-10 rounded-md px-3 sm:px-4 text-sm',
        'lg' => 'h-10 rounded-lg px-6 sm:px-8 text-base sm:text-lg',
        'icon' => 'h-10 w-10',
    ];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-lg font-medium transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-first-color/50 disabled:pointer-events-none disabled:opacity-50 {$variants[$variant]} {$sizes[$size]}"]) }}>
    {{ $slot }}
</button>

