@extends('layouts.admin')

@section('title', 'Rekap Absensi Bulanan')

@section('breadcrumb')
    <a href="{{ route('admin.absensi.index') }}"
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
        desc="Ringkasan kehadiran siswa per bulan beserta persentase kehadiran."
        :back="route('admin.absensi.index')"
    >
        <x-slot:actions>
            <x-ui.button variant="secondary" href="{{ route('admin.absensi.rekap-harian') }}">
                Rekap Harian
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Filter --}}
    <x-ui.section-card>
        <form method="GET" action="{{ route('admin.absensi.rekap-bulanan') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
                <div class="sm:w-44">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Bulan</label>
                    <select name="bulan"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        @foreach ($bulanList as $num => $nama)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:w-32">
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
                        <option value="">Semua Kelas</option>
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

    {{-- Info hari kerja --}}
    <div class="flex items-center gap-3 rounded-lg border border-[var(--border)] bg-[var(--primary-soft)]
                px-4 py-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-[var(--primary)]"
             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9-3.75h.008v.008H12V8.25z"/>
        </svg>
        <p class="text-sm text-[var(--primary)]">
            Periode: <strong>{{ $bulanList[$bulan] }} {{ $tahun }}</strong>
            — Hari efektif (Senin–Sabtu): <strong>{{ $hariKerja }} hari</strong>.
            Persentase dihitung dari jumlah hari hadir dibagi hari efektif.
        </p>
    </div>

    {{-- Tabel rekap bulanan --}}
    <x-ui.section-card
        :title="'Rekap Kehadiran ' . $bulanList[$bulan] . ' ' . $tahun"
        :noPadding="true"
    >
        <x-slot:actions>
            <span class="text-xs text-[var(--muted-foreground)]">{{ $rekap->count() }} siswa</span>
        </x-slot:actions>

        @if ($rekap->isEmpty())
            <x-ui.empty-state
                title="Tidak ada data siswa"
                message="Tidak ada siswa yang sesuai dengan filter yang dipilih."
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">No</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nama Siswa</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Kelas</th>
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
                                $pct = $item['persentase'];
                                $pctColor = $pct >= 90
                                    ? 'text-green-600'
                                    : ($pct >= 75 ? 'text-yellow-600' : 'text-red-600');
                                $barColor = $pct >= 90
                                    ? 'bg-green-500'
                                    : ($pct >= 75 ? 'bg-yellow-500' : 'bg-red-500');
                            @endphp
                            <tr class="hover:bg-[var(--muted)]/40 transition-colors">
                                <td class="px-5 py-3.5 tabular-nums text-[var(--muted-foreground)]">{{ $i + 1 }}</td>
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('admin.siswa.show', $item['siswa']) }}"
                                       class="font-medium text-[var(--foreground)] hover:text-[var(--primary)] transition-colors">
                                        {{ $item['siswa']->nama_lengkap }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                    {{ $item['siswa']->kelas->nama_kelas ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-center tabular-nums font-medium text-green-600">
                                    {{ $item['hadir'] }}
                                </td>
                                <td class="px-5 py-3.5 text-center tabular-nums font-medium text-yellow-600">
                                    {{ $item['izin'] }}
                                </td>
                                <td class="px-5 py-3.5 text-center tabular-nums font-medium text-blue-600">
                                    {{ $item['sakit'] }}
                                </td>
                                <td class="px-5 py-3.5 text-center tabular-nums font-medium text-red-600">
                                    {{ $item['alpha'] }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex-1 h-1.5 rounded-full bg-[var(--muted)] overflow-hidden">
                                            <div class="h-full rounded-full {{ $barColor }} transition-all"
                                                 style="width: {{ $pct }}%"></div>
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
                            <td colspan="3" class="px-5 py-3 text-sm font-semibold text-[var(--foreground)]">
                                Total
                            </td>
                            <td class="px-5 py-3 text-center tabular-nums font-bold text-green-600">
                                {{ $rekap->sum('hadir') }}
                            </td>
                            <td class="px-5 py-3 text-center tabular-nums font-bold text-yellow-600">
                                {{ $rekap->sum('izin') }}
                            </td>
                            <td class="px-5 py-3 text-center tabular-nums font-bold text-blue-600">
                                {{ $rekap->sum('sakit') }}
                            </td>
                            <td class="px-5 py-3 text-center tabular-nums font-bold text-red-600">
                                {{ $rekap->sum('alpha') }}
                            </td>
                            <td class="px-5 py-3 text-center text-xs text-[var(--muted-foreground)]">
                                Rata-rata:
                                <span class="font-semibold text-[var(--foreground)]">
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
