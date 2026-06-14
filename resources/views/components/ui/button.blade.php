@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'href' => null])
@php
$v = match($variant) {
    'secondary' => 'border border-[var(--border)] bg-[var(--card)] text-[var(--foreground)] hover:bg-[var(--muted)]',
    'danger'    => 'bg-[var(--destructive)] text-white hover:opacity-90',
    'ghost'     => 'text-[var(--muted-foreground)] hover:bg-[var(--muted)] hover:text-[var(--foreground)]',
    default     => 'bg-[var(--primary)] text-[var(--primary-foreground)] hover:opacity-90',
};
$sz = match($size) {
    'sm' => 'h-8 px-3 text-xs gap-1.5',
    'lg' => 'h-11 px-6 text-base gap-2.5',
    default => 'h-9 px-4 text-sm gap-2',
};
$base = "inline-flex items-center justify-center rounded-md font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--ring)] disabled:opacity-50 disabled:pointer-events-none {$v} {$sz}";
@endphp
@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $base]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $base]) }}>{{ $slot }}</button>
@endif