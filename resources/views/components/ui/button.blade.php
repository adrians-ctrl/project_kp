{{--
    Komponen: Button
    Props:
      - variant (string) — 'primary' | 'secondary' | 'danger' | 'ghost'
      - size    (string) — 'sm' | 'md' | 'lg'
      - type    (string) — 'button' | 'submit' | 'reset'
      - href    (string, optional) — jika diisi, render sebagai <a>
--}}

@props([
    'variant' => 'primary',
    'size'    => 'md',
    'type'    => 'button',
    'href'    => null,
])

@php
$variantClass = match($variant) {
    'secondary' => 'border border-[var(--border)] bg-[var(--card)] text-[var(--foreground)]
                    hover:bg-[var(--muted)] focus:ring-[var(--ring)]',
    'danger'    => 'bg-[var(--destructive)] text-white hover:opacity-90
                    focus:ring-[var(--destructive)]',
    'ghost'     => 'text-[var(--muted-foreground)] hover:bg-[var(--muted)]
                    hover:text-[var(--foreground)] focus:ring-[var(--ring)]',
    default     => 'bg-[var(--primary)] text-[var(--primary-foreground)]
                    hover:opacity-90 focus:ring-[var(--ring)]',
};

$sizeClass = match($size) {
    'sm'  => 'h-8 px-3 text-xs gap-1.5',
    'lg'  => 'h-11 px-6 text-base gap-2.5',
    default => 'h-9 px-4 text-sm gap-2',
};

$baseClass = "inline-flex items-center justify-center rounded-md font-medium
              transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2
              disabled:pointer-events-none disabled:opacity-50 {$variantClass} {$sizeClass}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $baseClass]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $baseClass]) }}>
        {{ $slot }}
    </button>
@endif
