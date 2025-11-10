@props(['class' => ''])

<div class="rounded-xl first-color dark:bg-slate-800 border-2 border-white/20 dark:border-slate-700 text-white dark:text-slate-100 shadow-md dark:shadow-xl hover:shadow-lg dark:hover:shadow-2xl transition-shadow duration-200 {{ $class }}">
    {{ $slot }}
</div>

