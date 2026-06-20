@extends('layouts.guru')

@section('title', 'Rekap Absensi Bulanan')

@section('breadcrumb')
    <a href="{{ route('guru.absensi.index') }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">Absensi</a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Rekap Bulanan</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Rekap Absensi Bulanan"
        desc="Ringkasan kehadiran siswa kelas Anda per bulan beserta persentase kehadiran."
        :back="route('guru.absensi.index')"
    >
        <x-slot:actions>
            <div x-data="{ open: false }" class="relative">
                <x-ui.button variant="secondary" @click="open = !open" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                    Ekspor
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                    </svg>
                </x-ui.button>
                <div x-show="open" @click.outside="open = false"
                     x-transition:enter="transition duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-1 w-44 rounded-lg border border-[var(--border)]
                            bg-[var(--card)] shadow-[var(--shadow-elevated)] z-20 py-1"
                     style="display:none">
                    <a href="{{ route('guru.absensi.export.pdf', request()->only(['kelas_id','bulan','tahun'])) }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-sm text-[var(--foreground)] hover:bg-[var(--muted)] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        Ekspor PDF
                    </a>
                    <a href="{{ route('guru.absensi.export.excel', request()->only(['kelas_id','bulan','tahun'])) }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-sm text-[var(--foreground)] hover:bg-[var(--muted)] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125"/>
                        </svg>
                        Ekspor Excel
                    </a>
                </div>
            </div>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Filter --}}
    <x-ui.section-card>
        <form method="GET" action="{{ route('guru.absensi.rekap-bulanan') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
                <div class="sm:w-44">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Bulan</label>
                    <select name="bulan"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        @foreach ($bulanList as $num => $nama)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:w-28">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Tahun</label>
                    <select name="tahun"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        @foreach ($tahunList as $t)
                            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:w-48">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Kelas</label>
                    <select name="kelas_id"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <x-ui.button type="submit">Tampilkan</x-ui.button>
            </div>
        </form>
    </x-ui.section-card>

    {{-- Tabel rekap --}}
    <x-ui.section-card
        :title="'Rekap Kehadiran ' . $namaBulan . ' ' . $tahun"
        :noPadding="true"
    >
        <x-slot:actions>
            <span class="text-xs text-[var(--muted-foreground)]">{{ $rekap->count() }} siswa</span>
        </x-slot:actions>

        @if ($rekap->isEmpty())
            <x-ui.empty-state
                title="Tidak ada data"
                message="Belum ada siswa atau data absensi untuk periode yang dipilih."
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">No</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nama Siswa</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-green-700">Hadir</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-yellow-700">Izin</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-blue-700">Sakit</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-red-700">Alpha</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">% Hadir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        @foreach ($rekap as $i => $item)
                            @php
                                $pct      = $item['persentase'];
                                $pctColor = $pct >= 90 ? 'text-green-600' : ($pct >= 75 ? 'text-yellow-600' : 'text-red-600');
                                $barColor = $pct >= 90 ? 'bg-green-500' : ($pct >= 75 ? 'bg-yellow-500' : 'bg-red-500');
                            @endphp
                            <tr class="hover:bg-[var(--muted)]/40 transition-colors">
                                <td class="px-5 py-3.5 tabular-nums text-[var(--muted-foreground)]">{{ $i + 1 }}</td>
                                <td class="px-5 py-3.5 font-medium text-[var(--foreground)]">
                                    {{ $item['siswa']->nama_lengkap }}
                                </td>
                                <td class="px-5 py-3.5 text-center tabular-nums font-medium text-green-600">{{ $item['hadir'] }}</td>
                                <td class="px-5 py-3.5 text-center tabular-nums font-medium text-yellow-600">{{ $item['izin'] }}</td>
                                <td class="px-5 py-3.5 text-center tabular-nums font-medium text-blue-600">{{ $item['sakit'] }}</td>
                                <td class="px-5 py-3.5 text-center tabular-nums font-medium text-red-600">{{ $item['alpha'] }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex-1 h-1.5 rounded-full bg-[var(--muted)] overflow-hidden">
                                            <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="tabular-nums text-xs font-semibold {{ $pctColor }} w-10 text-right">
                                            {{ $pct }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-[var(--border)] bg-[var(--muted)]/50">
                        <tr>
                            <td colspan="2" class="px-5 py-3 text-sm font-semibold text-[var(--foreground)]">Total</td>
                            <td class="px-5 py-3 text-center font-bold tabular-nums text-green-600">{{ $rekap->sum('hadir') }}</td>
                            <td class="px-5 py-3 text-center font-bold tabular-nums text-yellow-600">{{ $rekap->sum('izin') }}</td>
                            <td class="px-5 py-3 text-center font-bold tabular-nums text-blue-600">{{ $rekap->sum('sakit') }}</td>
                            <td class="px-5 py-3 text-center font-bold tabular-nums text-red-600">{{ $rekap->sum('alpha') }}</td>
                            <td class="px-5 py-3 text-center text-xs text-[var(--muted-foreground)]">
                                Rata-rata: <span class="font-semibold text-[var(--foreground)]">
                                    {{ $rekap->count() > 0 ? round($rekap->avg('persentase'), 1) : 0 }}%
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </x-ui.section-card>

@endsection
