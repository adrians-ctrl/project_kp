@props(['title' => null, 'description' => null, 'noPadding' => false])
<section {{ $attributes->merge(['class' => 'rounded-xl border border-[var(--border)] bg-[var(--card)] shadow-[var(--shadow-card)]']) }}>
    @if ($title || isset($actions))
        <header class="flex items-start justify-between gap-4 border-b border-[var(--border)] px-5 py-4">
            <div class="space-y-0.5">
                @if ($title)<h2 class="font-display text-base font-semibold tracking-tight text-[var(--foreground)]">{{ $title }}</h2>@endif
                @if ($description)<p class="text-xs text-[var(--muted-foreground)]">{{ $description }}</p>@endif
            </div>
            @isset($actions)<div class="flex items-center gap-2 shrink-0">{{ $actions }}</div>@endisset
        </header>
    @endif
    <div class="{{ $noPadding ? '' : 'p-5' }}">{{ $slot }}</div>
</section>