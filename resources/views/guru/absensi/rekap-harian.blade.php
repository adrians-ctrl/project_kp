@extends('layouts.guru')

@section('title', 'Rekap Absensi Harian')

@section('breadcrumb')
    <a href="{{ route('guru.absensi.index') }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">Absensi</a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Rekap Harian</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Rekap Absensi Harian"
        desc="Ringkasan kehadiran siswa kelas Anda pada tanggal tertentu."
        :back="route('guru.absensi.index')"
    >
        <x-slot:actions>
            <x-ui.button variant="secondary" href="{{ route('guru.absensi.rekap-bulanan') }}">
                Rekap Bulanan
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Filter --}}
    <x-ui.section-card>
        <form method="GET" action="{{ route('guru.absensi.rekap-harian') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="sm:w-48">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}"
                           class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                  px-3 text-sm outline-none transition text-[var(--foreground)]
                                  focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
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

    {{-- Ringkasan --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
        @php
            $cards = [
                ['label' => 'Total',  'value' => $ringkasan['total'], 'color' => 'text-[var(--foreground)]', 'bg' => 'bg-[var(--card)]'],
                ['label' => 'Hadir',  'value' => $ringkasan['hadir'], 'color' => 'text-green-600',  'bg' => 'bg-green-50'],
                ['label' => 'Izin',   'value' => $ringkasan['izin'],  'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50'],
                ['label' => 'Sakit',  'value' => $ringkasan['sakit'], 'color' => 'text-blue-600',   'bg' => 'bg-blue-50'],
                ['label' => 'Alpha',  'value' => $ringkasan['alpha'], 'color' => 'text-red-600',    'bg' => 'bg-red-50'],
            ];
        @endphp
        @foreach ($cards as $card)
            <div class="rounded-xl border border-[var(--border)] {{ $card['bg'] }} p-4 text-center">
                <div class="font-display text-3xl font-bold {{ $card['color'] }} tabular-nums">
                    {{ $card['value'] }}
                </div>
                <div class="mt-1 text-xs font-medium text-[var(--muted-foreground)] uppercase tracking-wide">
                    {{ $card['label'] }}
                </div>
            </div>
        @endforeach
    </div>

    {{-- Tabel --}}
    <x-ui.section-card
        :title="'Rekap Kehadiran — ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('l\, d F Y')"
        :noPadding="true"
    >
        <x-slot:actions>
            <span class="text-xs text-[var(--muted-foreground)]">{{ $absensi->count() }} catatan</span>
        </x-slot:actions>

        @if ($absensi->isEmpty())
            <x-ui.empty-state
                title="Belum ada data absensi"
                message="Belum ada absensi yang tercatat untuk tanggal yang dipilih."
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">No</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nama Siswa</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        @foreach ($absensi as $i => $item)
                            <tr class="hover:bg-[var(--muted)]/40 transition-colors">
                                <td class="px-5 py-3.5 tabular-nums text-[var(--muted-foreground)]">{{ $i + 1 }}</td>
                                <td class="px-5 py-3.5 font-medium text-[var(--foreground)]">
                                    {{ $item->siswa->nama_lengkap }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @php
                                        $tone = match($item->status) {
                                            'hadir' => 'success', 'izin' => 'warning',
                                            'sakit' => 'info', 'alpha' => 'destructive', default => 'neutral',
                                        };
                                    @endphp
                                    <x-ui.badge :tone="$tone">{{ $item->status_label }}</x-ui.badge>
                                </td>
                                <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                    {{ $item->keterangan ?? '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-ui.section-card>

@endsection
