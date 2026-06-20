@extends('layouts.guru')

@section('title', 'Rekap Nilai')

@section('breadcrumb')
    <a href="{{ route('guru.nilai.index') }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">Input Nilai</a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Rekap</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Rekap Nilai per Mata Pelajaran"
        desc="Ringkasan nilai siswa dikelompokkan berdasarkan mata pelajaran."
        :back="route('guru.nilai.index')"
    />

    {{-- Filter --}}
    <x-ui.section-card>
        <form method="GET" action="{{ route('guru.nilai.rekap') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">
                <div class="sm:w-48">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Kelas</label>
                    <select name="kelas_id"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)] px-3 text-sm
                                   outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
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
                <x-ui.button type="submit">Tampilkan</x-ui.button>
            </div>
        </form>
    </x-ui.section-card>

    @if ($nilaiByMapel->isEmpty())
        <x-ui.section-card>
            <x-ui.empty-state
                title="Belum ada data nilai"
                message="Belum ada nilai yang diinput untuk kelas, semester, dan tahun ajaran ini."
            >
                <x-ui.button href="{{ route('guru.nilai.index') }}" size="sm">Input Nilai</x-ui.button>
            </x-ui.empty-state>
        </x-ui.section-card>
    @else
        @foreach ($nilaiByMapel as $namaMapel => $data)
            <x-ui.section-card :noPadding="true">
                <div class="flex items-center justify-between border-b border-[var(--border)] px-5 py-4">
                    <div>
                        <h2 class="font-display text-base font-semibold text-[var(--foreground)]">
                            {{ $namaMapel }}
                        </h2>
                        <p class="text-xs text-[var(--muted-foreground)] mt-0.5">
                            {{ $data['items']->count() }} siswa tercatat
                        </p>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <div class="text-center">
                            <p class="text-[10px] uppercase tracking-wide text-[var(--muted-foreground)]">Rata-rata</p>
                            <p class="font-display font-bold text-[var(--primary)] tabular-nums">{{ $data['rata_rata'] }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] uppercase tracking-wide text-[var(--muted-foreground)]">Tertinggi</p>
                            <p class="font-display font-bold text-green-600 tabular-nums">{{ $data['tertinggi'] }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] uppercase tracking-wide text-[var(--muted-foreground)]">Terendah</p>
                            <p class="font-display font-bold text-red-600 tabular-nums">{{ $data['terendah'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">No</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nama Siswa</th>
                                <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Tugas</th>
                                <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">UTS</th>
                                <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">UAS</th>
                                <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nilai Akhir</th>
                                <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Grade</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Predikat</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)]">
                            @foreach ($data['items'] as $i => $item)
                                <tr class="hover:bg-[var(--muted)]/40 transition-colors">
                                    <td class="px-5 py-3.5 tabular-nums text-[var(--muted-foreground)]">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3.5 font-medium text-[var(--foreground)]">
                                        {{ $item->siswa->nama_lengkap ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-center tabular-nums">{{ $item->nilai_tugas }}</td>
                                    <td class="px-5 py-3.5 text-center tabular-nums">{{ $item->nilai_uts }}</td>
                                    <td class="px-5 py-3.5 text-center tabular-nums">{{ $item->nilai_uas }}</td>
                                    <td class="px-5 py-3.5 text-center font-semibold tabular-nums">
                                        {{ number_format($item->nilai_akhir, 1) }}
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
                    </table>
                </div>
            </x-ui.section-card>
        @endforeach
    @endif

@endsection
