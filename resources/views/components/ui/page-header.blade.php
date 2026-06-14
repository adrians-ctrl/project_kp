@props(['title', 'desc' => null, 'back' => null])
<div class="flex flex-col gap-4 border-b border-[var(--border)] pb-6 sm:flex-row sm:items-end sm:justify-between">
    <div class="space-y-1.5">
        @isset($breadcrumb)<nav class="flex items-center gap-1.5 text-xs text-[var(--muted-foreground)]">{{ $breadcrumb }}</nav>@endisset
        <div class="flex items-center gap-3">
            @if ($back)
                <a href="{{ $back }}" class="flex h-8 w-8 items-center justify-center rounded-md border border-[var(--border)] bg-[var(--card)] text-[var(--muted-foreground)] hover:bg-[var(--muted)] transition-colors shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                </a>
            @endif
            <h1 class="font-display text-2xl font-semibold tracking-tight text-[var(--foreground)]">{{ $title }}</h1>
        </div>
        @if ($desc)<p class="max-w-2xl text-sm text-[var(--muted-foreground)]">{{ $desc }}</p>@endif
    </div>
    @isset($actions)<div class="flex shrink-0 items-center gap-2">{{ $actions }}</div>@endisset
</div>