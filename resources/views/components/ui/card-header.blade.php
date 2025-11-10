@props(['class' => ''])

<div class="flex flex-col space-y-1.5 p-4 sm:p-5 md:p-6 {{ $class }}">
    {{ $slot }}
</div>

