{{--
    Komponen: Alert
    Props:
      - type    (string) — 'success' | 'error' | 'warning' | 'info'
      - message (string) — pesan yang ditampilkan
--}}

@props([
    'type'    => 'info',
    'message' => '',
])

@php
$styles = match($type) {
    'success' => ['bg' => 'bg-[var(--success)]/10', 'border' => 'border-[var(--success)]/30', 'text' => 'text-[var(--success)]', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>'],
    'error'   => ['bg' => 'bg-[var(--destructive)]/10', 'border' => 'border-[var(--destructive)]/30', 'text' => 'text-[var(--destructive)]', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9 3.75h.008v.008H12v-.008z"/>'],
    'warning' => ['bg' => 'bg-[var(--warning)]/10', 'border' => 'border-[var(--warning)]/30', 'text' => 'text-[var(--warning-foreground)]', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>'],
    default   => ['bg' => 'bg-[var(--info)]/10', 'border' => 'border-[var(--info)]/30', 'text' => 'text-[var(--info)]', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9-3.75h.008v.008H12V8.25z"/>'],
};
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-transition:leave="transition-opacity duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    role="alert"
    class="flex items-start gap-3 rounded-lg border px-4 py-3
           {{ $styles['bg'] }} {{ $styles['border'] }}"
>
    <svg xmlns="http://www.w3.org/2000/svg"
         class="mt-0.5 h-4 w-4 shrink-0 {{ $styles['text'] }}"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        {!! $styles['icon'] !!}
    </svg>

    <p class="flex-1 text-sm {{ $styles['text'] }}">{{ $message }}</p>

    <button
        @click="show = false"
        type="button"
        class="ml-auto shrink-0 {{ $styles['text'] }} opacity-60 hover:opacity-100 transition-opacity"
        aria-label="Tutup"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
        </svg>
    </button>
</div>
