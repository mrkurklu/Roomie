@props([
    'placeholder' => ''
])

<textarea placeholder="{{ $placeholder }}" {{ $attributes->merge(['class' => 'flex min-h-[100px] sm:min-h-[120px] w-full rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base text-gray-900 dark:text-slate-100 ring-offset-background placeholder:text-gray-400 dark:placeholder:text-slate-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-first-color/50 dark:focus-visible:ring-blue-500/50 focus-visible:border-first-color dark:focus-visible:border-blue-500 transition-all duration-200 disabled:cursor-not-allowed disabled:opacity-50 disabled:bg-gray-100 dark:disabled:bg-slate-700 resize-y']) }}>{{ $slot }}</textarea>

