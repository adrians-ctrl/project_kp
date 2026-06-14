@props(['title' => 'Tidak ada data', 'message' => null, 'icon' => null])
<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[var(--muted)] text-[var(--muted-foreground)] mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            {!! $icon ?? '<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>' !!}
        </svg>
    </div>
    <p class="font-display text-sm font-semibold text-[var(--foreground)]">{{ $title }}</p>
    @if ($message)
        <p class="mt-1 text-sm text-[var(--muted-foreground)] max-w-xs">{{ $message }}</p>
    @endif
    @if (!empty(trim($slot)))
        <div class="mt-4">{{ $slot }}</div>
    @endif
</div>