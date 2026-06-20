@extends('layouts.admin')

@section('title', 'Rekap Nilai')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Rekap Nilai</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Rekap Nilai"
        desc="Rekap nilai siswa per kelas, mata pelajaran, dan semester beserta grade dan predikat."
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
                    <a href="{{ route('admin.rekap.nilai.export.pdf', request()->only(['kelas_id','mapel_id','semester','tahun_ajaran'])) }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-sm text-[var(--foreground)] hover:bg-[var(--muted)] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        Ekspor PDF
                    </a>
                    <a href="{{ route('admin.rekap.nilai.export.excel', request()->only(['kelas_id','mapel_id','semester','tahun_ajaran'])) }}"
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
        <form method="GET" action="{{ route('admin.rekap.nilai') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
                <div class="sm:w-44">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Kelas</label>
                    <select name="kelas_id"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)] px-3 text-sm
                                   outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:w-52">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Mata Pelajaran</label>
                    <select name="mapel_id"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)] px-3 text-sm
                                   outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="">Semua Mapel</option>
                        @foreach ($mapelList as $mapel)
                            <option value="{{ $mapel->id }}" {{ $mapelId == $mapel->id ? 'selected' : '' }}>
                                {{ $mapel->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:w-32">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Semester</label>
                    <select name="semester"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)] px-3 text-sm
                                   outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="1" {{ $semester == '1' ? 'selected' : '' }}>Semester 1</option>
                        <option value="2" {{ $semester == '2' ? 'selected' : '' }}>Semester 2</option>
                    </select>
                </div>
                <div class="sm:w-36">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Tahun Ajaran</label>
                    <select name="tahun_ajaran"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)] px-3 text-sm
                                   outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        @foreach ($tahunAjaranList as $ta)
                            <option value="{{ $ta }}" {{ $tahunAjaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <x-ui.button type="submit">Tampilkan</x-ui.button>
                    <x-ui.button variant="secondary" href="{{ route('admin.rekap.nilai') }}">Reset</x-ui.button>
                </div>
            </div>
        </form>
    </x-ui.section-card>

    {{-- Statistik --}}
    @if ($nilaiQuery->count() > 0)
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-8">
            @foreach ([
                ['label'=>'Total Data',   'value'=>$stats['total'],     'color'=>'text-[var(--foreground)]',  'bg'=>'bg-[var(--card)]'],
                ['label'=>'Rata-rata',    'value'=>$stats['rata_rata'], 'color'=>'text-[var(--primary)]',     'bg'=>'bg-[var(--primary-soft)]'],
                ['label'=>'Nilai Max',    'value'=>$stats['nilai_max'], 'color'=>'text-green-600',  'bg'=>'bg-green-50'],
                ['label'=>'Nilai Min',    'value'=>$stats['nilai_min'], 'color'=>'text-red-600',    'bg'=>'bg-red-50'],
                ['label'=>'Grade A',      'value'=>$stats['grade_a'],   'color'=>'text-green-600',  'bg'=>'bg-green-50'],
                ['label'=>'Grade B',      'value'=>$stats['grade_b'],   'color'=>'text-blue-600',   'bg'=>'bg-blue-50'],
                ['label'=>'Grade C',      'value'=>$stats['grade_c'],   'color'=>'text-yellow-600', 'bg'=>'bg-yellow-50'],
                ['label'=>'Grade D/E',    'value'=>$stats['grade_d_e'], 'color'=>'text-red-600',    'bg'=>'bg-red-50'],
            ] as $card)
                <div class="rounded-xl border border-[var(--border)] {{ $card['bg'] }} p-3 text-center">
                    <div class="font-display text-xl font-bold {{ $card['color'] }} tabular-nums">
                        {{ $card['value'] }}
                    </div>
                    <div class="mt-0.5 text-[10px] font-medium text-[var(--muted-foreground)] uppercase tracking-wide">
                        {{ $card['label'] }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Tabel rekap --}}
    <x-ui.section-card :noPadding="true"
        :title="'Rekap Nilai — Semester ' . $semester . ' / ' . $tahunAjaran"
    >
        <x-slot:actions>
            <span class="text-xs text-[var(--muted-foreground)]">{{ $nilaiQuery->count() }} data</span>
        </x-slot:actions>

        @if ($nilaiQuery->isEmpty())
            <x-ui.empty-state
                title="Tidak ada data nilai"
                message="Sesuaikan filter di atas untuk menampilkan data rekap nilai."
            />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">No</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nama Siswa</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Kelas</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Mata Pelajaran</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Tugas</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">UTS</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">UAS</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nilai Akhir</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Grade</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Predikat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        @foreach ($nilaiQuery as $i => $item)
                            <tr class="hover:bg-[var(--muted)]/40 transition-colors">
                                <td class="px-5 py-3.5 tabular-nums text-[var(--muted-foreground)]">{{ $i + 1 }}</td>
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('admin.siswa.show', $item->siswa) }}"
                                       class="font-medium text-[var(--foreground)] hover:text-[var(--primary)] transition-colors">
                                        {{ $item->siswa->nama_lengkap }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                    {{ $item->siswa->kelas->nama_kelas ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-[var(--foreground)]">
                                    {{ $item->mapel->nama_mapel ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-center tabular-nums">{{ $item->nilai_tugas }}</td>
                                <td class="px-5 py-3.5 text-center tabular-nums">{{ $item->nilai_uts }}</td>
                                <td class="px-5 py-3.5 text-center tabular-nums">{{ $item->nilai_uas }}</td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="font-semibold tabular-nums text-[var(--foreground)]">
                                        {{ number_format($item->nilai_akhir, 1) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @php
                                        $tone = match($item->grade) {
                                            'A' => 'success', 'B' => 'info',
                                            'C' => 'warning', default => 'destructive',
                                        };
                                    @endphp
                                    <x-ui.badge :tone="$tone">{{ $item->grade }}</x-ui.badge>
                                </td>
                                <td class="px-5 py-3.5 text-[var(--muted-foreground)]">{{ $item->predikat }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    @if ($nilaiQuery->count() > 0)
                        <tfoot class="border-t-2 border-[var(--border)] bg-[var(--muted)]/50">
                            <tr>
                                <td colspan="7" class="px-5 py-3 text-sm font-semibold text-[var(--foreground)]">
                                    Rata-rata Keseluruhan
                                </td>
                                <td class="px-5 py-3 text-center font-bold text-[var(--primary)] tabular-nums">
                                    {{ number_format($stats['rata_rata'], 1) }}
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        @endif
    </x-ui.section-card>

@endsection
