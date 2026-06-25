@extends('layouts.public')

@section('title', 'Galeri — ' . ($profil?->nama_sekolah ?? 'SDN Babakan 02'))

@section('content')

    <section class="border-b border-[var(--border)] bg-[var(--card)] py-10">
        <div class="mx-auto max-w-6xl px-4 lg:px-6">
            <h1 class="font-display text-2xl font-bold text-[var(--foreground)] lg:text-3xl">Galeri Kegiatan</h1>
            <p class="mt-2 text-sm text-[var(--muted-foreground)]">
                Dokumentasi momen dan kegiatan {{ $profil?->nama_sekolah ?? 'sekolah' }}.
            </p>
        </div>
    </section>

    <section class="py-12 lg:py-16" x-data="{ lightbox: null }">
        <div class="mx-auto max-w-6xl px-4 lg:px-6">

            @if ($galeri->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[var(--muted-foreground)] mb-4"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                    </svg>
                    <p class="text-sm text-[var(--muted-foreground)]">Belum ada foto di galeri.</p>
                </div>
            @else
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($galeri as $item)
                        <button @click="lightbox = { url: '{{ $item->foto_url }}', judul: @js($item->judul), deskripsi: @js($item->deskripsi) }"
                                class="group text-left">
                            <div class="aspect-square overflow-hidden rounded-xl border border-[var(--border)] bg-[var(--muted)]">
                                <img src="{{ $item->foto_url }}" alt="{{ $item->judul }}"
                                     class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                            </div>
                            <p class="mt-2 text-sm font-medium text-[var(--foreground)] line-clamp-1">{{ $item->judul }}</p>
                        </button>
                    @endforeach
                </div>

                @if ($galeri->hasPages())
                    <div class="mt-10">
                        {{ $galeri->links('components.ui.pagination') }}
                    </div>
                @endif
            @endif

        </div>

        {{-- Lightbox --}}
        <div x-show="lightbox" x-transition.opacity
             @keydown.escape.window="lightbox = null"
             class="fixed inset-0 z-50 flex items-center justify-center p-6" style="display:none">
            <div class="absolute inset-0 bg-black/80" @click="lightbox = null"></div>
            <div class="relative max-w-3xl w-full">
                <img :src="lightbox?.url" :alt="lightbox?.judul" class="w-full max-h-[75vh] object-contain rounded-lg">
                <div class="mt-3 text-center">
                    <p class="text-white font-medium" x-text="lightbox?.judul"></p>
                    <p x-show="lightbox?.deskripsi" class="text-white/70 text-sm mt-1" x-text="lightbox?.deskripsi"></p>
                </div>
                <button @click="lightbox = null" type="button"
                        class="absolute -top-3 -right-3 flex h-9 w-9 items-center justify-center rounded-full bg-white text-[var(--foreground)] shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>

@endsection
