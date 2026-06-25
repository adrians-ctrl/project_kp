@extends('layouts.guru')

@section('title', 'Rekap')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Guru</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Rekap</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Rekap"
        desc="Pilih jenis rekap yang ingin Anda lihat untuk kelas yang diampu."
    />

    @if (! $guru || $kelasList->isEmpty())
        <x-ui.section-card>
            <x-ui.empty-state
                title="Tidak ada kelas yang dapat direkap"
                message="Pastikan akun Anda terhubung ke data guru dan memiliki kelas yang diampu."
            />
        </x-ui.section-card>
    @else
        <div class="grid gap-4 sm:grid-cols-2">

            <a href="{{ route('guru.nilai.rekap') }}"
               class="group flex flex-col gap-3 rounded-xl border border-[var(--border)] bg-[var(--card)]
                      p-6 hover:shadow-[var(--shadow-elevated)] transition-shadow">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-[var(--primary-soft)] text-[var(--primary)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-base font-semibold text-[var(--foreground)] group-hover:text-[var(--primary)] transition-colors">
                        Rekap Nilai
                    </h2>
                    <p class="mt-1 text-sm text-[var(--muted-foreground)]">
                        Lihat rekap nilai siswa dikelompokkan per mata pelajaran, termasuk rata-rata dan grade.
                    </p>
                </div>
            </a>

            <a href="{{ route('guru.absensi.rekap-bulanan') }}"
               class="group flex flex-col gap-3 rounded-xl border border-[var(--border)] bg-[var(--card)]
                      p-6 hover:shadow-[var(--shadow-elevated)] transition-shadow">
                <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-green-50 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-base font-semibold text-[var(--foreground)] group-hover:text-[var(--primary)] transition-colors">
                        Rekap Absensi
                    </h2>
                    <p class="mt-1 text-sm text-[var(--muted-foreground)]">
                        Lihat rekap kehadiran siswa per bulan, lengkap dengan ekspor PDF dan Excel.
                    </p>
                </div>
            </a>

        </div>
    @endif

@endsection
