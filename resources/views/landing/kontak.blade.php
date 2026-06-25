@extends('layouts.public')

@section('title', 'Kontak — ' . ($profil?->nama_sekolah ?? 'SDN Babakan 02'))

@section('content')

    <section class="border-b border-[var(--border)] bg-[var(--card)] py-10">
        <div class="mx-auto max-w-6xl px-4 lg:px-6">
            <h1 class="font-display text-2xl font-bold text-[var(--foreground)] lg:text-3xl">Hubungi Kami</h1>
            <p class="mt-2 text-sm text-[var(--muted-foreground)]">
                Sampaikan pertanyaan atau masukan Anda kepada {{ $profil?->nama_sekolah ?? 'sekolah' }}.
            </p>
        </div>
    </section>

    <section class="py-12 lg:py-16">
        <div class="mx-auto max-w-5xl px-4 lg:px-6">
            <div class="grid gap-8 lg:grid-cols-5">

                {{-- Info kontak --}}
                <div class="lg:col-span-2 space-y-5">
                    <div class="rounded-xl border border-[var(--border)] bg-[var(--card)] p-6">
                        <h2 class="font-display text-base font-semibold text-[var(--foreground)] mb-4">
                            Informasi Kontak
                        </h2>
                        <div class="space-y-4 text-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[var(--primary-soft)] text-[var(--primary)]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-[var(--foreground)]">Alamat</p>
                                    <p class="text-[var(--muted-foreground)]">{{ $profil?->alamat_lengkap ?? '-' }}</p>
                                </div>
                            </div>

                            @if ($profil?->telepon)
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[var(--primary-soft)] text-[var(--primary)]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-[var(--foreground)]">Telepon</p>
                                        <p class="text-[var(--muted-foreground)]">{{ $profil->telepon }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($profil?->email)
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[var(--primary-soft)] text-[var(--primary)]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-[var(--foreground)]">Email</p>
                                        <p class="text-[var(--muted-foreground)] break-all">{{ $profil->email }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($profil?->website)
                                <div class="flex items-start gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[var(--primary-soft)] text-[var(--primary)]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-[var(--foreground)]">Website</p>
                                        <p class="text-[var(--muted-foreground)] break-all">{{ $profil->website }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Form kontak --}}
                <div class="lg:col-span-3">
                    <div class="rounded-xl border border-[var(--border)] bg-[var(--card)] p-6">
                        <h2 class="font-display text-base font-semibold text-[var(--foreground)] mb-5">
                            Kirim Pesan
                        </h2>

                        @if (session('success'))
                            <div class="mb-5">
                                <x-ui.alert type="success" :message="session('success')" />
                            </div>
                        @endif

                        <form method="POST" action="{{ route('landing.kontak.kirim') }}" class="space-y-4">
                            @csrf

                            <div class="grid gap-4 sm:grid-cols-2">
                                <x-ui.input name="nama" label="Nama Lengkap" :required="true"
                                    placeholder="Nama Anda" :value="old('nama')" />
                                <x-ui.input name="email" label="Email" type="email" :required="true"
                                    placeholder="email@domain.com" :value="old('email')" />
                            </div>

                            <x-ui.input name="subjek" label="Subjek" :required="true"
                                placeholder="Topik pesan Anda" :value="old('subjek')" />

                            <x-ui.textarea name="pesan" label="Pesan" :required="true" :rows="5"
                                placeholder="Tulis pesan Anda di sini..." />

                            <x-ui.button type="submit" class="w-full justify-center">
                                Kirim Pesan
                            </x-ui.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
