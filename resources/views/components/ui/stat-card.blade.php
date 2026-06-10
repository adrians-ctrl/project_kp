{{--
    Komponen: Stat Card
    Props:
      - label  (string) — label statistik
      - value  (string) — nilai utama
      - delta  (string, optional) — teks perubahan
      - trend  (string, optional) — 'up' | 'down' | 'neutral'
      - icon   (string, optional) — SVG path(s) untuk ikon
--}}

@props([
    'label',
    'value',
    'delta' => null,
    'trend' => 'neutral',
    'icon'  => null,
])

@php
$trendColor = match($trend) {
    'up'      => 'text-[var(--success)]',
    'down'    => 'text-[var(--destructive)]',
    default   => 'text-[var(--muted-foreground)]',
};
@endphp

<div class="group rounded-xl border border-[var(--border)] bg-[var(--card)]
            p-5 shadow-[var(--shadow-card)]
            transition-shadow hover:shadow-[var(--shadow-elevated)]">

    <div class="flex items-start justify-between">
        <span class="text-xs font-medium uppercase tracking-wide
                     text-[var(--muted-foreground)]">
            {{ $label }}
        </span>

        @if ($icon)
            <div class="flex h-9 w-9 items-center justify-center rounded-md
                        bg-[var(--primary-soft)] text-[var(--primary)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                     fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    {!! $icon !!}
                </svg>
            </div>
        @endif
    </div>

    <div class="mt-3 font-display text-3xl font-semibold tracking-tight
                text-[var(--foreground)]">
        {{ $value }}
    </div>

    @if ($delta)
        <div class="mt-2 flex items-center gap-1 text-xs font-medium {{ $trendColor }}">
            @if ($trend === 'up')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25"/>
                </svg>
            @elseif ($trend === 'down')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 4.5 15 15m0 0V8.25m0 11.25H8.25"/>
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>
                </svg>
            @endif
            <span>{{ $delta }}</span>
        </div>
    @endif

</div>
