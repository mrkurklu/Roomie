@props(['class' => ''])

<div class="rounded-xl bg-white dark:bg-surface-dark border border-primary-light/10 dark:border-primary-dark/10 text-text-light dark:text-text-dark shadow-sm hover:shadow-md transition-shadow duration-200 {{ $class }}">
    {{ $slot }}
</div>

