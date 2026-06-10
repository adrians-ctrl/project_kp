{{--
    Komponen: Badge
    Props:
      - tone  (string) — 'success' | 'warning' | 'destructive' | 'info' | 'neutral'
--}}

@props([
    'tone' => 'neutral',
])

@php
$styles = match($tone) {
    'success'     => 'bg-[var(--success)]/12 text-[var(--success)] ring-[var(--success)]/20',
    'warning'     => 'bg-[var(--warning)]/15 text-[var(--warning-foreground)] ring-[var(--warning)]/30',
    'destructive' => 'bg-[var(--destructive)]/12 text-[var(--destructive)] ring-[var(--destructive)]/20',
    'info'        => 'bg-[var(--info)]/12 text-[var(--info)] ring-[var(--info)]/20',
    default       => 'bg-[var(--muted)] text-[var(--muted-foreground)] ring-[var(--border)]',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {$styles}"]) }}>
    {{ $slot }}
</span>
