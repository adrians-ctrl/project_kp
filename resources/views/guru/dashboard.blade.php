@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Guru</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Dashboard</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Dashboard Guru"
        :desc="'Selamat datang, ' . Auth::user()->name . '. ' . $today->translatedFormat('l, d F Y') . '.'"
    />

    @if (! $guru)
        {{-- Guru belum terhubung ke data guru_staf --}}
        <x-ui.section-card>
            <x-ui.empty-state
                title="Akun Anda belum terhubung ke data guru"
                message="Hubungi administrator untuk menautkan akun Anda ke data guru agar dapat mengakses fitur kelas, absensi, dan nilai."
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>'
            />
        </x-ui.section-card>
    @else

        {{-- Stat Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($stats as $stat)
                <x-ui.stat-card
                    :label="$stat['label']"
                    :value="$stat['value']"
                    :delta="$stat['delta']"
                    :trend="$stat['trend']"
                    :icon="$stat['icon']"
                />
            @endforeach
        </div>

        @if ($kelasList->isEmpty())
            <x-ui.section-card>
                <x-ui.empty-state
                    title="Belum ada kelas yang diampu"
                    message="Anda belum ditetapkan sebagai wali kelas. Hubungi administrator untuk informasi lebih lanjut."
                />
            </x-ui.section-card>
        @else

            {{-- Row: Absensi hari ini + Ringkasan --}}
            <div class="grid gap-6 lg:grid-cols-3">

                <x-ui.section-card
                    class="lg:col-span-2"
                    title="Absensi Hari Ini"
                    :description="'Catatan kehadiran kelas Anda — ' . $today->translatedFormat('d F Y')"
                    :noPadding="true"
                >
                    <x-slot:actions>
                        <a href="{{ route('guru.absensi.index') }}"
                           class="inline-flex items-center gap-1 text-xs font-medium text-[var(--primary)] hover:underline">
                            Input Absensi
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                            </svg>
                        </a>
                    </x-slot:actions>

                    @if ($absensiHariIni->isEmpty())
                        <x-ui.empty-state
                            title="Belum ada absensi hari ini"
                            message="Klik Input Absensi untuk mencatat kehadiran siswa."
                        />
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Siswa</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Kelas</th>
                                        <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[var(--border)]">
                                    @foreach ($absensiHariIni as $absen)
                                        <tr class="hover:bg-[var(--muted)]/40 transition-colors">
                                            <td class="px-5 py-3 font-medium text-[var(--foreground)]">{{ $absen->siswa->nama_lengkap ?? '-' }}</td>
                                            <td class="px-5 py-3 text-[var(--muted-foreground)]">{{ $absen->siswa->kelas->nama_kelas ?? '-' }}</td>
                                            <td class="px-5 py-3 text-right">
                                                @php $tone = match($absen->status) { 'hadir'=>'success','izin'=>'warning','sakit'=>'info','alpha'=>'destructive',default=>'neutral' }; @endphp
                                                <x-ui.badge :tone="$tone">{{ $absen->status_label }}</x-ui.badge>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </x-ui.section-card>

                <x-ui.section-card title="Ringkasan Kehadiran"
                    :description="'Hari ini, ' . $today->translatedFormat('d F Y')">
                    <div class="mb-5">
                        <div class="flex items-end justify-between mb-2">
                            <span class="text-xs text-[var(--muted-foreground)]">Tingkat kehadiran</span>
                            <span class="font-display text-2xl font-semibold text-[var(--foreground)]">
                                {{ $ringkasanAbsensi['persentaseHadir'] }}%
                            </span>
                        </div>
                        <div class="h-2 w-full rounded-full bg-[var(--muted)] overflow-hidden">
                            <div class="h-full rounded-full bg-green-500 transition-all duration-500"
                                 style="width: {{ $ringkasanAbsensi['persentaseHadir'] }}%"></div>
                        </div>
                    </div>
                    <div class="space-y-2.5">
                        @foreach ([['Hadir','hadir','bg-green-500'],['Izin','izin','bg-yellow-500'],['Sakit','sakit','bg-blue-500'],['Alpha','alpha','bg-red-500']] as [$lbl,$key,$color])
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2.5">
                                    <div class="h-2 w-2 rounded-full {{ $color }}"></div>
                                    <span class="text-[var(--muted-foreground)]">{{ $lbl }}</span>
                                </div>
                                <span class="font-medium tabular-nums text-[var(--foreground)]">
                                    {{ $ringkasanAbsensi[$key] }} <span class="text-xs font-normal text-[var(--muted-foreground)]">siswa</span>
                                </span>
                            </div>
                        @endforeach
                    </div>
                    @if ($ringkasanAbsensi['total'] > 0)
                        <div class="mt-4 border-t border-[var(--border)] pt-4 flex justify-between text-sm">
                            <span class="text-[var(--muted-foreground)]">Total tercatat</span>
                            <span class="font-semibold">{{ $ringkasanAbsensi['total'] }} siswa</span>
                        </div>
                    @endif
                </x-ui.section-card>
            </div>

            {{-- Row: Nilai rata-rata per kelas --}}
            <x-ui.section-card
                title="Nilai Rata-rata per Kelas"
                description="Rata-rata nilai akhir seluruh mata pelajaran yang sudah diinput"
            >
                <x-slot:actions>
                    <a href="{{ route('guru.nilai.index') }}"
                       class="inline-flex items-center gap-1 text-xs font-medium text-[var(--primary)] hover:underline">
                        Input Nilai
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </a>
                </x-slot:actions>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($nilaiPerKelas as $item)
                        @php
                            $avg = $item['rata_rata'];
                            $color = $avg === null ? 'text-[var(--muted-foreground)]'
                                : ($avg >= 80 ? 'text-green-600' : ($avg >= 70 ? 'text-blue-600' : ($avg >= 60 ? 'text-yellow-600' : 'text-red-600')));
                        @endphp
                        <div class="rounded-xl border border-[var(--border)] p-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-sm font-medium text-[var(--foreground)]">
                                    {{ $item['kelas']->nama_kelas }}
                                </p>
                                <x-ui.badge tone="neutral">{{ $item['kelas']->siswa_count ?? $item['kelas']->siswa()->count() }} siswa</x-ui.badge>
                            </div>
                            @if ($avg !== null)
                                <div class="font-display text-3xl font-bold {{ $color }} tabular-nums">
                                    {{ number_format($avg, 1) }}
                                </div>
                                <p class="mt-1 text-xs text-[var(--muted-foreground)]">
                                    Dari {{ $item['jumlah_data'] }} data nilai
                                </p>
                            @else
                                <p class="text-sm text-[var(--muted-foreground)]">Belum ada data nilai</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-ui.section-card>

        @endif
    @endif

@endsection
