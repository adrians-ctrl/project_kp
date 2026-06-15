@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Dashboard</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Dashboard Administrator"
        desc="Ringkasan statistik akademik dan aktivitas sistem, {{ $today->translatedFormat('l\, d F Y') }}."
    />

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

    {{-- Row 2: Absensi + Ringkasan --}}
    <div class="grid gap-6 lg:grid-cols-3">

        <x-ui.section-card class="lg:col-span-2" title="Absensi Hari Ini"
            :description="'Catatan kehadiran per ' . $today->translatedFormat('d F Y')"
            :noPadding="true">
            <x-slot:actions>
                <a href="{{ route('admin.absensi.index') }}"
                   class="inline-flex items-center gap-1 text-xs font-medium text-[var(--primary)] hover:underline">
                    Lihat semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            </x-slot:actions>

            @if ($absensiHariIni->isEmpty())
                <x-ui.empty-state title="Belum ada data absensi hari ini"
                    message="Data akan muncul setelah guru mengisi absensi kelas." />
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

        {{-- Ringkasan Kehadiran --}}
        <x-ui.section-card title="Ringkasan Kehadiran" :description="'Hari ini, ' . $today->translatedFormat('d F Y')">
            <div class="mb-5">
                <div class="flex items-end justify-between mb-2">
                    <span class="text-xs text-[var(--muted-foreground)]">Tingkat kehadiran</span>
                    <span class="font-display text-2xl font-semibold text-[var(--foreground)]">{{ $ringkasanAbsensi['persentaseHadir'] }}%</span>
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

    {{-- Row 3: Distribusi Kelas + Berita --}}
    <div class="grid gap-6 lg:grid-cols-3">

        <x-ui.section-card class="lg:col-span-2" title="Distribusi Siswa per Kelas"
            description="Jumlah siswa aktif pada setiap kelas">
            <x-slot:actions>
                <a href="{{ route('admin.kelas.index') }}" class="inline-flex items-center gap-1 text-xs font-medium text-[var(--primary)] hover:underline">
                    Kelola kelas
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            </x-slot:actions>

            @if ($siswaPerKelas->isEmpty())
                <x-ui.empty-state title="Belum ada kelas" message="Tambahkan data kelas terlebih dahulu.">
                    <x-ui.button href="{{ route('admin.kelas.index') }}" size="sm">Tambah Kelas</x-ui.button>
                </x-ui.empty-state>
            @else
                @php $maxSiswa = $siswaPerKelas->max('siswa_count') ?: 1; @endphp
                <div class="space-y-3">
                    @foreach ($siswaPerKelas as $kelas)
                        @php $pct = round(($kelas->siswa_count / $maxSiswa) * 100); @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-[var(--foreground)]">{{ $kelas->nama_kelas }}</span>
                                    @if ($kelas->waliKelas)
                                        <span class="text-xs text-[var(--muted-foreground)]">— {{ $kelas->waliKelas->nama_lengkap }}</span>
                                    @endif
                                </div>
                                <span class="text-sm tabular-nums font-medium text-[var(--foreground)]">
                                    {{ $kelas->siswa_count }} <span class="text-xs font-normal text-[var(--muted-foreground)]">siswa</span>
                                </span>
                            </div>
                            <div class="h-2 w-full rounded-full bg-[var(--muted)] overflow-hidden">
                                <div class="h-full rounded-full bg-[var(--primary)] transition-all duration-500"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 flex justify-between border-t border-[var(--border)] pt-4 text-sm">
                    <span class="text-[var(--muted-foreground)]">Total seluruh kelas</span>
                    <span class="font-semibold">{{ $siswaPerKelas->sum('siswa_count') }} siswa</span>
                </div>
            @endif
        </x-ui.section-card>

        <x-ui.section-card title="Berita Terbaru" description="Publikasi pada landing page">
            <x-slot:actions>
                <a href="{{ route('admin.berita.index') }}" class="inline-flex items-center gap-1 text-xs font-medium text-[var(--primary)] hover:underline">
                    Kelola
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            </x-slot:actions>

            @if ($beritaTerbaru->isEmpty())
                <x-ui.empty-state title="Belum ada berita" message="Tambahkan berita atau pengumuman." />
            @else
                <ul class="space-y-4">
                    @foreach ($beritaTerbaru as $berita)
                        <li class="border-b border-[var(--border)] pb-4 last:border-0 last:pb-0">
                            <div class="flex items-center gap-2 mb-1.5">
                                <x-ui.badge :tone="$berita->kategori === 'pengumuman' ? 'warning' : 'info'">
                                    {{ ucfirst($berita->kategori) }}
                                </x-ui.badge>
                                <span class="text-[11px] text-[var(--muted-foreground)]">{{ $berita->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="text-sm font-medium text-[var(--foreground)] leading-snug line-clamp-2">
                                {{ $berita->judul }}
                            </h3>
                        </li>
                    @endforeach
                </ul>
            @endif
        </x-ui.section-card>
    </div>

    {{-- Akses Cepat --}}
    <x-ui.section-card title="Akses Cepat" description="Pintasan ke fitur yang sering digunakan">
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @php
                $shortcuts = [
                    ['label'=>'Tambah Siswa',  'href'=>route('admin.siswa.index'),    'bg'=>'bg-blue-50',   'text'=>'text-blue-700'],
                    ['label'=>'Input Absensi', 'href'=>route('admin.absensi.index'),  'bg'=>'bg-green-50',  'text'=>'text-green-700'],
                    ['label'=>'Input Nilai',   'href'=>route('admin.nilai.index'),    'bg'=>'bg-indigo-50', 'text'=>'text-indigo-700'],
                    ['label'=>'Rekap Nilai',   'href'=>route('admin.rekap.nilai'),    'bg'=>'bg-yellow-50', 'text'=>'text-yellow-700'],
                    ['label'=>'Rekap Absensi', 'href'=>route('admin.rekap.absensi'), 'bg'=>'bg-red-50',    'text'=>'text-red-700'],
                    ['label'=>'Tambah Berita', 'href'=>route('admin.berita.index'),  'bg'=>'bg-purple-50', 'text'=>'text-purple-700'],
                ];
            @endphp
            @foreach ($shortcuts as $s)
                <a href="{{ $s['href'] }}"
                   class="group flex flex-col items-center gap-3 rounded-xl border border-[var(--border)]
                          p-4 text-center transition-all hover:border-[var(--ring)] hover:shadow-[var(--shadow-elevated)]">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg {{ $s['bg'] }} {{ $s['text'] }}
                                transition-transform group-hover:scale-110">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                    </div>
                    <span class="text-xs font-medium leading-tight text-[var(--foreground)]">{{ $s['label'] }}</span>
                </a>
            @endforeach
        </div>
    </x-ui.section-card>

@endsection
