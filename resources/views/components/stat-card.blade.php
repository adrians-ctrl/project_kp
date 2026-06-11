{{--
    Komponen: Stat Card
    Props:
      - label  (string)           — label statistik
      - value  (string)           — nilai utama
      - delta  (string, optional) — teks perubahan
      - trend  (string, optional) — 'up' | 'down' | 'neutral'
      - icon   (string, optional) — key: 'users' | 'academic' | 'school' | 'book'
                                    atau SVG path string langsung
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
    'up'    => 'text-[var(--success)]',
    'down'  => 'text-[var(--destructive)]',
    default => 'text-[var(--muted-foreground)]',
};

$iconPaths = [
    'users'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>',
    'academic' => '<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 013.74-3.342"/>',
    'school'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>',
    'book'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>',
];

$resolvedIcon = isset($iconPaths[$icon]) ? $iconPaths[$icon] : $icon;
@endphp

<div class="group rounded-xl border border-[var(--border)] bg-[var(--card)]
            p-5 shadow-[var(--shadow-card)]
            transition-shadow hover:shadow-[var(--shadow-elevated)]">

    <div class="flex items-start justify-between">
        <span class="text-xs font-medium uppercase tracking-wide
                     text-[var(--muted-foreground)]">
            {{ $label }}
        </span>

        @if ($resolvedIcon)
            <div class="flex h-9 w-9 items-center justify-center rounded-md
                        bg-[var(--primary-soft)] text-[var(--primary)]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                     fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    {!! $resolvedIcon !!}
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
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m4.5 19.5 15-15m0 0H8.25m11.25 0v11.25"/>
                </svg>
            @elseif ($trend === 'down')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m4.5 4.5 15 15m0 0V8.25m0 11.25H8.25"/>
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
