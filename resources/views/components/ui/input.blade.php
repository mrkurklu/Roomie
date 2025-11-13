@props([
    'type' => 'text',
    'placeholder' => ''
])

<input type="{{ $type }}" placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'flex h-10 sm:h-11 w-full rounded-lg border border-primary-light/20 dark:border-primary-dark/20 bg-white dark:bg-background-dark px-3 sm:px-4 py-2 text-sm sm:text-base text-text-light dark:text-text-dark ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-light dark:focus-visible:ring-primary-dark focus-visible:border-primary-light dark:focus-visible:border-primary-dark transition-all duration-200 disabled:cursor-not-allowed disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-slate-700']) }}>

