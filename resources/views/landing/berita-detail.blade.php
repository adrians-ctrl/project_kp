@extends('layouts.public')

@section('title', $berita->judul . ' — ' . ($profil?->nama_sekolah ?? 'SDN Babakan 02'))
@section('meta-description', $berita->konten_ringkas)

@section('content')

    <article class="py-12 lg:py-16">
        <div class="mx-auto max-w-3xl px-4 lg:px-6">

            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 text-xs text-[var(--muted-foreground)] mb-6">
                <a href="{{ route('landing') }}" class="hover:text-[var(--primary)] transition-colors">Beranda</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
                <a href="{{ route('landing.berita') }}" class="hover:text-[var(--primary)] transition-colors">Berita</a>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                </svg>
                <span class="truncate max-w-[200px]">{{ $berita->judul }}</span>
            </nav>

            {{-- Header artikel --}}
            <div class="mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <x-ui.badge :tone="$berita->kategori === 'pengumuman' ? 'warning' : 'info'">
                        {{ ucfirst($berita->kategori) }}
                    </x-ui.badge>
                    <span class="text-xs text-[var(--muted-foreground)]">
                        {{ $berita->created_at->translatedFormat('d F Y') }}
                    </span>
                </div>
                <h1 class="font-display text-2xl font-bold text-[var(--foreground)] leading-tight lg:text-3xl">
                    {{ $berita->judul }}
                </h1>
                @if ($berita->user)
                    <p class="mt-3 text-sm text-[var(--muted-foreground)]">
                        Ditulis oleh <span class="font-medium text-[var(--foreground)]">{{ $berita->user->name }}</span>
                    </p>
                @endif
            </div>

            {{-- Gambar utama --}}
            @if ($berita->gambar_url)
                <div class="aspect-video w-full overflow-hidden rounded-xl border border-[var(--border)] bg-[var(--muted)] mb-8">
                    <img src="{{ $berita->gambar_url }}" alt="{{ $berita->judul }}" class="h-full w-full object-cover">
                </div>
            @endif

            {{-- Konten --}}
            <div class="prose-content text-[var(--foreground)] leading-relaxed whitespace-pre-line">
                {{ $berita->konten }}
            </div>

            {{-- Share / back --}}
            <div class="mt-10 flex items-center justify-between border-t border-[var(--border)] pt-6">
                <a href="{{ route('landing.berita') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-medium text-[var(--primary)] hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                    </svg>
                    Kembali ke daftar berita
                </a>
            </div>
        </div>
    </article>

    {{-- Berita lainnya --}}
    @if ($beritaLain->isNotEmpty())
        <section class="border-t border-[var(--border)] bg-[var(--muted)]/40 py-12 lg:py-16">
            <div class="mx-auto max-w-6xl px-4 lg:px-6">
                <h2 class="font-display text-xl font-bold text-[var(--foreground)] mb-8">Berita Lainnya</h2>
                <div class="grid gap-6 sm:grid-cols-3">
                    @foreach ($beritaLain as $item)
                        <a href="{{ route('landing.berita.detail', $item->slug) }}"
                           class="group rounded-xl border border-[var(--border)] bg-[var(--card)] overflow-hidden
                                  hover:shadow-[var(--shadow-elevated)] transition-shadow">
                            <div class="aspect-video bg-[var(--muted)] overflow-hidden">
                                @if ($item->gambar_url)
                                    <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}"
                                         class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @endif
                            </div>
                            <div class="p-4">
                                <x-ui.badge :tone="$item->kategori === 'pengumuman' ? 'warning' : 'info'">
                                    {{ ucfirst($item->kategori) }}
                                </x-ui.badge>
                                <h3 class="mt-2 text-sm font-semibold text-[var(--foreground)] leading-snug line-clamp-2
                                           group-hover:text-[var(--primary)] transition-colors">
                                    {{ $item->judul }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
