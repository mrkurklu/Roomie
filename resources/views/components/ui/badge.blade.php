@props([
    'variant' => 'default'
])

@php
    $variants = [
        'default' => 'bg-primary text-primary-foreground',
        'secondary' => 'bg-secondary text-secondary-foreground',
        'outline' => 'border border-input bg-background',
        'destructive' => 'bg-destructive text-destructive-foreground',
    ];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold transition-colors {$variants[$variant]}"]) }}>
    {{ $slot }}
</span>

