@extends('layouts.public')

@section('title', ($profil?->nama_sekolah ?? 'SDN Babakan 02') . ' — Beranda')

@section('content')

    {{-- ============================================================
         HERO
    ============================================================= --}}
    <section class="relative overflow-hidden bg-gradient-to-br from-[var(--primary)] via-[var(--primary)] to-[#1e3a5f] py-16 lg:py-24">
        <div class="absolute inset-0 opacity-10"
             style="background-image: radial-gradient(circle at 20% 20%, white 1px, transparent 1px); background-size: 24px 24px;"></div>

        <div class="relative mx-auto max-w-4xl px-4 text-center lg:px-6">
            @if ($profil?->akreditasi)
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-4 py-1.5 text-xs font-medium text-white mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                    </svg>
                    Terakreditasi {{ $profil->akreditasi }}
                </span>
            @endif

            <h1 class="font-display text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                {{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}
            </h1>

            @if ($visiMisi)
                <p class="mt-5 text-base text-white/85 leading-relaxed max-w-2xl mx-auto lg:text-lg">
                    {{ $visiMisi->visi }}
                </p>
            @endif

            <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                <a href="{{ route('landing.berita') }}"
                   class="inline-flex items-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold
                          text-[var(--primary)] hover:bg-white/90 transition-colors">
                    Lihat Berita Terbaru
                </a>
                <a href="{{ route('landing.kontak') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-white/40 px-6 py-3 text-sm
                          font-semibold text-white hover:bg-white/10 transition-colors">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================
         INFO CEPAT
    ============================================================= --}}
    @if ($profil)
        <section class="border-b border-[var(--border)] bg-[var(--card)]">
            <div class="mx-auto max-w-6xl px-4 py-8 lg:px-6">
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
                    @php
                        $infoItems = array_filter([
                            $profil->npsn ? ['label' => 'NPSN', 'value' => $profil->npsn] : null,
                            $profil->akreditasi ? ['label' => 'Akreditasi', 'value' => $profil->akreditasi] : null,
                            $profil->kota ? ['label' => 'Lokasi', 'value' => $profil->kota] : null,
                            $profil->telepon ? ['label' => 'Telepon', 'value' => $profil->telepon] : null,
                        ]);
                    @endphp
                    @foreach ($infoItems as $item)
                        <div class="text-center">
                            <p class="font-display text-lg font-bold text-[var(--primary)]">{{ $item['value'] }}</p>
                            <p class="mt-0.5 text-xs uppercase tracking-wide text-[var(--muted-foreground)]">{{ $item['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         VISI & MISI
    ============================================================= --}}
    @if ($visiMisi)
        <section class="py-16 lg:py-20">
            <div class="mx-auto max-w-5xl px-4 lg:px-6">
                <div class="text-center mb-10">
                    <h2 class="font-display text-2xl font-bold text-[var(--foreground)] lg:text-3xl">Visi & Misi</h2>
                    <p class="mt-2 text-sm text-[var(--muted-foreground)]">Arah dan tujuan pendidikan kami</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="rounded-2xl border border-[var(--border)] bg-[var(--primary-soft)] p-8">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[var(--primary)] mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--primary-foreground)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-lg font-bold text-[var(--foreground)] mb-3">Visi</h3>
                        <p class="text-sm text-[var(--muted-foreground)] leading-relaxed">{{ $visiMisi->visi }}</p>
                    </div>

                    <div class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-8">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-[var(--accent)] mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--accent-foreground)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-display text-lg font-bold text-[var(--foreground)] mb-3">Misi</h3>
                        <ul class="space-y-2 text-sm text-[var(--muted-foreground)] leading-relaxed">
                            @foreach (preg_split('/\r\n|\r|\n/', trim($visiMisi->misi)) as $poin)
                                @if (trim($poin) !== '')
                                    <li class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 shrink-0 text-[var(--primary)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                        <span>{{ ltrim($poin, '0123456789. ') }}</span>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         SAMBUTAN KEPALA SEKOLAH
    ============================================================= --}}
    @if ($profil?->sambutan)
        <section class="bg-[var(--muted)]/40 py-16 lg:py-20">
            <div class="mx-auto max-w-4xl px-4 lg:px-6">
                <div class="rounded-2xl border border-[var(--border)] bg-[var(--card)] p-8 lg:p-10">
                    <div class="flex items-center gap-2 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[var(--primary)]/30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9.983 3v7.391c0 5.704-3.731 9.57-8.983 10.609l-.995-2.151c2.432-.917 3.995-3.638 3.995-5.849h-4v-10h9.983zm14.017 0v7.391c0 5.704-3.748 9.571-9 10.609l-.996-2.151c2.433-.917 3.996-3.638 3.996-5.849h-3.983v-10h9.983z"/>
                        </svg>
                    </div>
                    <p class="text-sm text-[var(--foreground)] leading-relaxed whitespace-pre-line">
                        {{ $profil->sambutan }}
                    </p>
                    @php $kepsek = $guru->firstWhere('jabatan', 'Kepala Sekolah'); @endphp
                    @if ($kepsek)
                        <div class="mt-6 flex items-center gap-3 border-t border-[var(--border)] pt-5">
                            <img src="{{ $kepsek->foto_url }}" alt="{{ $kepsek->nama_lengkap }}"
                                 class="h-12 w-12 rounded-full object-cover ring-2 ring-[var(--border)]">
                            <div>
                                <p class="text-sm font-semibold text-[var(--foreground)]">{{ $kepsek->nama_lengkap }}</p>
                                <p class="text-xs text-[var(--muted-foreground)]">Kepala Sekolah</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         DAFTAR GURU
    ============================================================= --}}
    @if ($guru->isNotEmpty())
        <section class="py-16 lg:py-20">
            <div class="mx-auto max-w-6xl px-4 lg:px-6">
                <div class="text-center mb-10">
                    <h2 class="font-display text-2xl font-bold text-[var(--foreground)] lg:text-3xl">Tenaga Pendidik</h2>
                    <p class="mt-2 text-sm text-[var(--muted-foreground)]">Guru dan staf yang berdedikasi untuk pendidikan terbaik</p>
                </div>

                <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($guru as $item)
                        <div class="flex flex-col items-center text-center rounded-xl border border-[var(--border)]
                                    bg-[var(--card)] p-5 hover:shadow-[var(--shadow-elevated)] transition-shadow">
                            <img src="{{ $item->foto_url }}" alt="{{ $item->nama_lengkap }}"
                                 class="h-16 w-16 rounded-full object-cover ring-2 ring-[var(--border)] mb-3">
                            <p class="text-sm font-semibold text-[var(--foreground)] leading-tight">
                                {{ $item->nama_lengkap }}
                            </p>
                            <p class="mt-1 text-xs text-[var(--muted-foreground)]">{{ $item->jabatan }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         BERITA TERBARU
    ============================================================= --}}
    @if ($berita->isNotEmpty())
        <section class="bg-[var(--muted)]/40 py-16 lg:py-20">
            <div class="mx-auto max-w-6xl px-4 lg:px-6">
                <div class="flex items-end justify-between mb-10">
                    <div>
                        <h2 class="font-display text-2xl font-bold text-[var(--foreground)] lg:text-3xl">Berita & Pengumuman</h2>
                        <p class="mt-2 text-sm text-[var(--muted-foreground)]">Kabar terbaru dari sekolah kami</p>
                    </div>
                    <a href="{{ route('landing.berita') }}"
                       class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-[var(--primary)] hover:underline">
                        Lihat semua
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </a>
                </div>

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($berita as $item)
                        <a href="{{ route('landing.berita.detail', $item->slug) }}"
                           class="group rounded-xl border border-[var(--border)] bg-[var(--card)] overflow-hidden
                                  hover:shadow-[var(--shadow-elevated)] transition-shadow">
                            <div class="aspect-video bg-[var(--muted)] overflow-hidden">
                                @if ($item->gambar_url)
                                    <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}"
                                         class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="flex h-full items-center justify-center text-[var(--muted-foreground)]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-2">
                                    <x-ui.badge :tone="$item->kategori === 'pengumuman' ? 'warning' : 'info'">
                                        {{ ucfirst($item->kategori) }}
                                    </x-ui.badge>
                                    <span class="text-[11px] text-[var(--muted-foreground)]">{{ $item->created_at->diffForHumans() }}</span>
                                </div>
                                <h3 class="font-semibold text-[var(--foreground)] leading-snug line-clamp-2
                                           group-hover:text-[var(--primary)] transition-colors">
                                    {{ $item->judul }}
                                </h3>
                                <p class="mt-2 text-sm text-[var(--muted-foreground)] line-clamp-2">
                                    {{ $item->konten_ringkas }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8 text-center sm:hidden">
                    <a href="{{ route('landing.berita') }}"
                       class="inline-flex items-center gap-1 text-sm font-medium text-[var(--primary)] hover:underline">
                        Lihat semua berita
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         GALERI
    ============================================================= --}}
    @if ($galeri->isNotEmpty())
        <section class="py-16 lg:py-20" x-data="{ lightbox: null }">
            <div class="mx-auto max-w-6xl px-4 lg:px-6">
                <div class="flex items-end justify-between mb-10">
                    <div>
                        <h2 class="font-display text-2xl font-bold text-[var(--foreground)] lg:text-3xl">Galeri Kegiatan</h2>
                        <p class="mt-2 text-sm text-[var(--muted-foreground)]">Momen-momen kegiatan sekolah</p>
                    </div>
                    <a href="{{ route('landing.galeri') }}"
                       class="hidden sm:inline-flex items-center gap-1 text-sm font-medium text-[var(--primary)] hover:underline">
                        Lihat semua
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                    @foreach ($galeri as $item)
                        <button @click="lightbox = { url: '{{ $item->foto_url }}', judul: @js($item->judul) }"
                                class="group aspect-square overflow-hidden rounded-xl border border-[var(--border)] bg-[var(--muted)]">
                            <img src="{{ $item->foto_url }}" alt="{{ $item->judul }}"
                                 class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Lightbox --}}
            <div x-show="lightbox" x-transition.opacity
                 @keydown.escape.window="lightbox = null"
                 class="fixed inset-0 z-50 flex items-center justify-center p-6" style="display:none">
                <div class="absolute inset-0 bg-black/80" @click="lightbox = null"></div>
                <div class="relative max-w-3xl w-full">
                    <img :src="lightbox?.url" :alt="lightbox?.judul" class="w-full max-h-[75vh] object-contain rounded-lg">
                    <p class="mt-3 text-center text-white font-medium" x-text="lightbox?.judul"></p>
                    <button @click="lightbox = null" type="button"
                            class="absolute -top-3 -right-3 flex h-9 w-9 items-center justify-center rounded-full bg-white text-[var(--foreground)] shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </section>
    @endif

    {{-- ============================================================
         CTA KONTAK
    ============================================================= --}}
    <section class="bg-[var(--primary)] py-14">
        <div class="mx-auto max-w-3xl px-4 text-center lg:px-6">
            <h2 class="font-display text-xl font-bold text-white lg:text-2xl">
                Ingin Tahu Lebih Lanjut?
            </h2>
            <p class="mt-2 text-sm text-white/80">
                Hubungi kami untuk informasi pendaftaran dan kegiatan sekolah.
            </p>
            <a href="{{ route('landing.kontak') }}"
               class="mt-6 inline-flex items-center gap-2 rounded-lg bg-white px-6 py-3 text-sm font-semibold
                      text-[var(--primary)] hover:bg-white/90 transition-colors">
                Hubungi Kami
            </a>
        </div>
    </section>

@endsection
