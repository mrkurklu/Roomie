@props([
    'placeholder' => ''
])

<textarea placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'flex min-h-[100px] sm:min-h-[120px] w-full rounded-lg border border-primary-light/20 dark:border-primary-dark/20 bg-white dark:bg-background-dark px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base text-text-light dark:text-text-dark ring-offset-background placeholder:text-text-light/50 dark:placeholder:text-text-dark/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary-light dark:focus-visible:ring-primary-dark focus-visible:border-primary-light dark:focus-visible:border-primary-dark transition-all duration-200 disabled:cursor-not-allowed disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-slate-700 resize-y']) }}>{{ $slot }}</textarea>

