@props(['tone' => 'neutral'])
@php
$s = match($tone) {
    'success'     => 'bg-green-50 text-green-700 ring-green-200',
    'warning'     => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
    'destructive' => 'bg-red-50 text-red-700 ring-red-200',
    'info'        => 'bg-blue-50 text-blue-700 ring-blue-200',
    default       => 'bg-[var(--muted)] text-[var(--muted-foreground)] ring-[var(--border)]',
};
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {$s}"]) }}>
    {{ $slot }}
</span>