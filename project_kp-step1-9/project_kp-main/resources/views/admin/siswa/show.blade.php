@extends('layouts.admin')

@section('title', $siswa->nama_lengkap)

@section('breadcrumb')
    <a href="{{ route('admin.siswa.index') }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">Siswa</a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)] truncate max-w-[200px]">{{ $siswa->nama_lengkap }}</span>
@endsection

@section('content')

    <x-ui.page-header
        :title="$siswa->nama_lengkap"
        :back="route('admin.siswa.index')"
    >
        <x-slot:actions>
            <x-ui.button variant="secondary" href="{{ route('admin.siswa.rapor', $siswa) }}"
                         target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z"/>
                </svg>
                Cetak Rapor
            </x-ui.button>
            <x-ui.button href="{{ route('admin.siswa.edit', $siswa) }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                </svg>
                Edit Data
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Kolom kiri: Profil --}}
        <div class="space-y-6">

            {{-- Kartu profil --}}
            <x-ui.section-card>
                <div class="flex flex-col items-center text-center gap-3">
                    <img src="{{ $siswa->foto_url }}"
                         alt="{{ $siswa->nama_lengkap }}"
                         class="h-24 w-24 rounded-full object-cover ring-4 ring-[var(--border)]">
                    <div>
                        <h2 class="font-display text-lg font-semibold text-[var(--foreground)]">
                            {{ $siswa->nama_lengkap }}
                        </h2>
                        <p class="text-sm text-[var(--muted-foreground)]">
                            {{ $siswa->kelas->nama_kelas ?? '—' }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <x-ui.badge :tone="$siswa->jenis_kelamin === 'L' ? 'info' : 'neutral'">
                            {{ $siswa->jenis_kelamin_label }}
                        </x-ui.badge>
                        @if ($siswa->umur)
                            <x-ui.badge tone="neutral">{{ $siswa->umur }} tahun</x-ui.badge>
                        @endif
                    </div>
                </div>
            </x-ui.section-card>

            {{-- Data identitas --}}
            <x-ui.section-card title="Identitas">
                <dl class="space-y-3">
                    @foreach ([
                        ['label' => 'NISN',        'value' => $siswa->nisn],
                        ['label' => 'NIS',         'value' => $siswa->nis],
                        ['label' => 'Tempat Lahir','value' => $siswa->tempat_lahir ?? '—'],
                        ['label' => 'Tanggal Lahir','value' => $siswa->tanggal_lahir_format],
                        ['label' => 'Alamat',      'value' => $siswa->alamat ?? '—'],
                        ['label' => 'No. HP',      'value' => $siswa->no_hp ?? '—'],
                        ['label' => 'Orang Tua',   'value' => $siswa->nama_orang_tua ?? '—'],
                    ] as $item)
                        <div class="flex flex-col gap-0.5">
                            <dt class="text-xs font-medium text-[var(--muted-foreground)]">
                                {{ $item['label'] }}
                            </dt>
                            <dd class="text-sm text-[var(--foreground)]">
                                {{ $item['value'] }}
                            </dd>
                        </div>
                        @if (!$loop->last)
                            <div class="border-t border-[var(--border)]"></div>
                        @endif
                    @endforeach
                </dl>
            </x-ui.section-card>

            {{-- Wali kelas --}}
            @if ($siswa->kelas?->waliKelas)
                <x-ui.section-card title="Wali Kelas">
                    <div class="flex items-center gap-3">
                        <img src="{{ $siswa->kelas->waliKelas->foto_url }}"
                             alt="{{ $siswa->kelas->waliKelas->nama_lengkap }}"
                             class="h-10 w-10 rounded-full object-cover ring-1 ring-[var(--border)]">
                        <div>
                            <p class="text-sm font-medium text-[var(--foreground)]">
                                {{ $siswa->kelas->waliKelas->nama_lengkap }}
                            </p>
                            <p class="text-xs text-[var(--muted-foreground)]">
                                {{ $siswa->kelas->waliKelas->jabatan }}
                            </p>
                        </div>
                    </div>
                </x-ui.section-card>
            @endif
        </div>

        {{-- Kolom kanan: Akademik --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Ringkasan absensi --}}
            <x-ui.section-card title="Ringkasan Kehadiran"
                description="Data {{ $ringkasanAbsensi['total'] }} hari tercatat">
                <div class="grid grid-cols-4 gap-4">
                    @foreach ([
                        ['label' => 'Hadir',  'key' => 'hadir',  'color' => 'text-green-600',  'bg' => 'bg-green-50'],
                        ['label' => 'Izin',   'key' => 'izin',   'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50'],
                        ['label' => 'Sakit',  'key' => 'sakit',  'color' => 'text-blue-600',   'bg' => 'bg-blue-50'],
                        ['label' => 'Alpha',  'key' => 'alpha',  'color' => 'text-red-600',    'bg' => 'bg-red-50'],
                    ] as $item)
                        <div class="flex flex-col items-center gap-1.5 rounded-xl {{ $item['bg'] }} p-4">
                            <span class="font-display text-2xl font-bold {{ $item['color'] }}">
                                {{ $ringkasanAbsensi[$item['key']] }}
                            </span>
                            <span class="text-xs font-medium text-[var(--muted-foreground)]">
                                {{ $item['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </x-ui.section-card>

            {{-- Nilai per mapel --}}
            <x-ui.section-card title="Nilai Akademik" description="Rata-rata nilai per mata pelajaran">
                <x-slot:actions>
                    <a href="{{ route('admin.nilai.index') }}"
                       class="text-xs font-medium text-[var(--primary)] hover:underline">
                        Input Nilai
                    </a>
                </x-slot:actions>

                @if ($nilaiPerMapel->isEmpty())
                    <x-ui.empty-state title="Belum ada data nilai"
                        message="Nilai akan tampil setelah guru menginput nilai untuk siswa ini." />
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-[var(--border)]">
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Mata Pelajaran</th>
                                    <th class="pb-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nilai Akhir</th>
                                    <th class="pb-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Grade</th>
                                    <th class="pb-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Predikat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border)]">
                                @foreach ($nilaiPerMapel as $n)
                                    <tr>
                                        <td class="py-3 font-medium text-[var(--foreground)]">{{ $n['mapel'] }}</td>
                                        <td class="py-3 text-center tabular-nums font-semibold text-[var(--foreground)]">
                                            {{ number_format($n['nilai_akhir'], 1) }}
                                        </td>
                                        <td class="py-3 text-center">
                                            @php
                                                $tone = match($n['grade']) {
                                                    'A' => 'success',
                                                    'B' => 'info',
                                                    'C' => 'warning',
                                                    default => 'destructive',
                                                };
                                            @endphp
                                            <x-ui.badge :tone="$tone">{{ $n['grade'] }}</x-ui.badge>
                                        </td>
                                        <td class="py-3 text-[var(--muted-foreground)]">{{ $n['predikat'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-ui.section-card>

            {{-- Riwayat absensi terbaru --}}
            <x-ui.section-card title="Riwayat Absensi Terbaru"
                description="30 catatan terakhir" :noPadding="true">
                @if ($siswa->absensi->isEmpty())
                    <x-ui.empty-state title="Belum ada data absensi" />
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Tanggal</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Status</th>
                                    <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--border)]">
                                @foreach ($siswa->absensi as $absen)
                                    <tr class="hover:bg-[var(--muted)]/40 transition-colors">
                                        <td class="px-5 py-3 tabular-nums text-[var(--muted-foreground)]">
                                            {{ $absen->tanggal->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-5 py-3">
                                            @php
                                                $tone = match($absen->status) {
                                                    'hadir' => 'success',
                                                    'izin'  => 'warning',
                                                    'sakit' => 'info',
                                                    'alpha' => 'destructive',
                                                    default => 'neutral',
                                                };
                                            @endphp
                                            <x-ui.badge :tone="$tone">{{ $absen->status_label }}</x-ui.badge>
                                        </td>
                                        <td class="px-5 py-3 text-[var(--muted-foreground)]">
                                            {{ $absen->keterangan ?? '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-ui.section-card>

        </div>
    </div>

@endsection
