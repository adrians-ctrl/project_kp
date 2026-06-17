@extends('layouts.admin')

@section('title', 'Nilai Siswa')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Nilai Siswa</span>
@endsection

@section('content')

<div x-data="nilaiApp()" x-init="init()">

    <x-ui.page-header
        title="Nilai Siswa"
        desc="Input dan kelola nilai akademik siswa per mata pelajaran, semester, dan tahun ajaran."
    >
        <x-slot:actions>
            <x-ui.button @click="modalTambah = true" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Input Nilai
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Filter --}}
    <x-ui.section-card>
        <form method="GET" action="{{ route('admin.nilai.index') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end">

                <div class="sm:w-44">
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

                <div class="sm:w-52">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Mata Pelajaran</label>
                    <select name="mapel_id"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
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
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="1" {{ $semester == '1' ? 'selected' : '' }}>Semester 1</option>
                        <option value="2" {{ $semester == '2' ? 'selected' : '' }}>Semester 2</option>
                    </select>
                </div>

                <div class="sm:w-36">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Tahun Ajaran</label>
                    <select name="tahun_ajaran"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        @foreach ($tahunAjaranList as $ta)
                            <option value="{{ $ta }}" {{ $tahunAjaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <x-ui.button type="submit">Tampilkan</x-ui.button>
                    <x-ui.button variant="secondary" href="{{ route('admin.nilai.index') }}">Reset</x-ui.button>
                </div>
            </div>
        </form>
    </x-ui.section-card>

    {{-- Tabel nilai --}}
    <x-ui.section-card :noPadding="true">
        <x-slot:actions>
            <span class="text-xs text-[var(--muted-foreground)]">{{ $nilai->total() }} data nilai</span>
        </x-slot:actions>

        @if ($nilai->isEmpty())
            <x-ui.empty-state
                title="Belum ada data nilai"
                message="Gunakan tombol Input Nilai atau sesuaikan filter untuk menampilkan data."
            >
                <x-ui.button @click="modalTambah = true" size="sm" type="button">Input Nilai</x-ui.button>
            </x-ui.empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">No</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Siswa</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Kelas</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Mata Pelajaran</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Tugas</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">UTS</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">UAS</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nilai Akhir</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Grade</th>
                            <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        @foreach ($nilai as $i => $item)
                            <tr class="hover:bg-[var(--muted)]/40 transition-colors"
                                x-data="{ modalEdit: false, modalHapus: false }">
                                <td class="px-5 py-3.5 tabular-nums text-[var(--muted-foreground)]">
                                    {{ $nilai->firstItem() + $i }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('admin.siswa.show', $item->siswa) }}"
                                       class="font-medium text-[var(--foreground)] hover:text-[var(--primary)] transition-colors">
                                        {{ $item->siswa->nama_lengkap }}
                                    </a>
                                    <div class="text-xs text-[var(--muted-foreground)] font-mono">
                                        {{ $item->siswa->nis }}
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                    {{ $item->siswa->kelas->nama_kelas ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-[var(--foreground)]">
                                    {{ $item->mapel->nama_mapel ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-center tabular-nums text-[var(--foreground)]">
                                    {{ $item->nilai_tugas }}
                                </td>
                                <td class="px-5 py-3.5 text-center tabular-nums text-[var(--foreground)]">
                                    {{ $item->nilai_uts }}
                                </td>
                                <td class="px-5 py-3.5 text-center tabular-nums text-[var(--foreground)]">
                                    {{ $item->nilai_uas }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="font-semibold tabular-nums text-[var(--foreground)]">
                                        {{ number_format($item->nilai_akhir, 1) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @php
                                        $tone = match($item->grade) {
                                            'A' => 'success',
                                            'B' => 'info',
                                            'C' => 'warning',
                                            default => 'destructive',
                                        };
                                    @endphp
                                    <x-ui.badge :tone="$tone">
                                        {{ $item->grade }} — {{ $item->predikat }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1">

                                        {{-- Edit --}}
                                        <button
                                            @click="openEdit({{ json_encode([
                                                'id'           => $item->id,
                                                'siswa_id'     => $item->siswa_id,
                                                'mapel_id'     => $item->mapel_id,
                                                'semester'     => $item->semester,
                                                'tahun_ajaran' => $item->tahun_ajaran,
                                                'nilai_tugas'  => $item->nilai_tugas,
                                                'nilai_uts'    => $item->nilai_uts,
                                                'nilai_uas'    => $item->nilai_uas,
                                                'siswa_nama'   => $item->siswa->nama_lengkap,
                                                'mapel_nama'   => $item->mapel->nama_mapel ?? '',
                                            ]) }})"
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-md
                                                   text-[var(--muted-foreground)] hover:bg-[var(--muted)]
                                                   hover:text-[var(--foreground)] transition-colors"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                            </svg>
                                        </button>

                                        {{-- Hapus --}}
                                        <button @click="modalHapus = true" type="button"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-md
                                                       text-[var(--muted-foreground)] hover:bg-red-50
                                                       hover:text-red-600 transition-colors" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <x-ui.confirm-delete
                                        name="modalHapus"
                                        :action="route('admin.nilai.destroy', $item)"
                                        :label="'nilai ' . ($item->siswa->nama_lengkap ?? '') . ' — ' . ($item->mapel->nama_mapel ?? '')"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($nilai->hasPages())
                <div class="border-t border-[var(--border)] px-5 py-4">
                    {{ $nilai->links('components.ui.pagination') }}
                </div>
            @endif
        @endif
    </x-ui.section-card>

    {{-- ================================================================
         MODAL: INPUT NILAI BARU
    ================================================================= --}}
    <div x-show="modalTambah"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="modalTambah = false"
         class="fixed inset-0 z-50 flex items-start justify-center p-4 pt-16"
         style="display:none">

        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="modalTambah = false"></div>

        <div x-show="modalTambah"
             x-transition:enter="transition duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-lg rounded-xl border border-[var(--border)]
                    bg-[var(--card)] shadow-[var(--shadow-elevated)]">

            <div class="flex items-center justify-between border-b border-[var(--border)] px-5 py-4">
                <h2 class="font-display text-base font-semibold text-[var(--foreground)]">Input Nilai Siswa</h2>
                <button @click="modalTambah = false" type="button"
                        class="flex h-8 w-8 items-center justify-center rounded-md
                               text-[var(--muted-foreground)] hover:bg-[var(--muted)] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.nilai.store') }}">
                @csrf
                <div class="space-y-4 px-5 py-5">

                    {{-- Pilih Kelas (untuk filter siswa via AJAX) --}}
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-[var(--foreground)]">Kelas</label>
                        <select @change="loadSiswa($event.target.value)"
                                class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                       px-3 text-sm outline-none transition text-[var(--foreground)]
                                       focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                            <option value="">Pilih kelas dulu...</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id }}"
                                        {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Siswa --}}
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-[var(--foreground)]">
                            Siswa <span class="text-[var(--destructive)]">*</span>
                        </label>
                        <select name="siswa_id" required x-model="form.siswa_id"
                                class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                       px-3 text-sm outline-none transition text-[var(--foreground)]
                                       focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20"
                                :disabled="siswaList.length === 0">
                            <option value="">
                                <span x-text="siswaList.length === 0 ? 'Pilih kelas terlebih dahulu' : 'Pilih siswa...'"></span>
                            </option>
                            <template x-for="s in siswaList" :key="s.id">
                                <option :value="s.id" x-text="s.nama_lengkap + ' (' + s.nis + ')'"></option>
                            </template>
                        </select>
                        @error('siswa_id')<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
                    </div>

                    {{-- Mapel --}}
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-[var(--foreground)]">
                            Mata Pelajaran <span class="text-[var(--destructive)]">*</span>
                        </label>
                        <select name="mapel_id" required x-model="form.mapel_id"
                                class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                       px-3 text-sm outline-none transition text-[var(--foreground)]
                                       focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                            <option value="">Pilih mata pelajaran...</option>
                            @foreach ($mapelList as $mapel)
                                <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                            @endforeach
                        </select>
                        @error('mapel_id')<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
                    </div>

                    {{-- Semester & Tahun Ajaran --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-[var(--foreground)]">
                                Semester <span class="text-[var(--destructive)]">*</span>
                            </label>
                            <select name="semester" required x-model="form.semester"
                                    class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                           px-3 text-sm outline-none transition text-[var(--foreground)]
                                           focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-[var(--foreground)]">
                                Tahun Ajaran <span class="text-[var(--destructive)]">*</span>
                            </label>
                            <select name="tahun_ajaran" required x-model="form.tahun_ajaran"
                                    class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                           px-3 text-sm outline-none transition text-[var(--foreground)]
                                           focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                                @foreach ($tahunAjaranList as $ta)
                                    <option value="{{ $ta }}" {{ $tahunAjaran == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Komponen Nilai --}}
                    <div class="rounded-lg border border-[var(--border)] bg-[var(--muted)]/40 p-4 space-y-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-[var(--muted-foreground)]">
                            Komponen Nilai (0 – 100)
                        </p>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-[var(--foreground)]">
                                    Tugas <span class="text-[var(--destructive)]">*</span>
                                </label>
                                <input type="number" name="nilai_tugas" min="0" max="100" step="0.01" required
                                       x-model="form.nilai_tugas" @input="hitungNilaiAkhir()"
                                       class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                              px-3 text-sm text-center tabular-nums outline-none transition
                                              focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20"
                                       placeholder="0 – 100">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-[var(--foreground)]">
                                    UTS <span class="text-[var(--destructive)]">*</span>
                                </label>
                                <input type="number" name="nilai_uts" min="0" max="100" step="0.01" required
                                       x-model="form.nilai_uts" @input="hitungNilaiAkhir()"
                                       class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                              px-3 text-sm text-center tabular-nums outline-none transition
                                              focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20"
                                       placeholder="0 – 100">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-[var(--foreground)]">
                                    UAS <span class="text-[var(--destructive)]">*</span>
                                </label>
                                <input type="number" name="nilai_uas" min="0" max="100" step="0.01" required
                                       x-model="form.nilai_uas" @input="hitungNilaiAkhir()"
                                       class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                              px-3 text-sm text-center tabular-nums outline-none transition
                                              focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20"
                                       placeholder="0 – 100">
                            </div>
                        </div>

                        {{-- Preview nilai akhir --}}
                        <div x-show="nilaiAkhir !== null"
                             class="flex items-center justify-between rounded-md bg-[var(--card)]
                                    border border-[var(--border)] px-4 py-2.5">
                            <span class="text-sm text-[var(--muted-foreground)]">Nilai Akhir (rata-rata)</span>
                            <div class="flex items-center gap-3">
                                <span class="font-display text-xl font-bold text-[var(--foreground)] tabular-nums"
                                      x-text="nilaiAkhir?.toFixed(1)"></span>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset"
                                      :class="gradeClass" x-text="grade + ' — ' + predikat"></span>
                            </div>
                        </div>
                    </div>

                    @if ($errors->any())
                        <x-ui.alert type="error" :message="$errors->first()" />
                    @endif
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-[var(--border)] px-5 py-4">
                    <x-ui.button variant="secondary" type="button" @click="modalTambah = false">Batal</x-ui.button>
                    <x-ui.button type="submit">Simpan Nilai</x-ui.button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================================================================
         MODAL: EDIT NILAI
    ================================================================= --}}
    <div x-show="modalEditOpen"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="modalEditOpen = false"
         class="fixed inset-0 z-50 flex items-start justify-center p-4 pt-16"
         style="display:none">

        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="modalEditOpen = false"></div>

        <div x-show="modalEditOpen"
             x-transition:enter="transition duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-md rounded-xl border border-[var(--border)]
                    bg-[var(--card)] shadow-[var(--shadow-elevated)]">

            <div class="flex items-center justify-between border-b border-[var(--border)] px-5 py-4">
                <div>
                    <h2 class="font-display text-base font-semibold text-[var(--foreground)]">Edit Nilai</h2>
                    <p class="text-xs text-[var(--muted-foreground)] mt-0.5"
                       x-text="editData.siswa_nama + ' — ' + editData.mapel_nama"></p>
                </div>
                <button @click="modalEditOpen = false" type="button"
                        class="flex h-8 w-8 items-center justify-center rounded-md
                               text-[var(--muted-foreground)] hover:bg-[var(--muted)] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form :action="'/admin/nilai/' + editData.id" method="POST">
                @csrf
                @method('PUT')
                {{-- Hidden fields --}}
                <input type="hidden" name="siswa_id"     :value="editData.siswa_id">
                <input type="hidden" name="mapel_id"     :value="editData.mapel_id">
                <input type="hidden" name="semester"     :value="editData.semester">
                <input type="hidden" name="tahun_ajaran" :value="editData.tahun_ajaran">

                <div class="space-y-4 px-5 py-5">

                    <div class="rounded-lg bg-[var(--muted)]/40 px-4 py-3 text-sm">
                        <div class="grid grid-cols-2 gap-2 text-[var(--muted-foreground)]">
                            <span>Semester</span>
                            <span class="font-medium text-[var(--foreground)]" x-text="editData.semester"></span>
                            <span>Tahun Ajaran</span>
                            <span class="font-medium text-[var(--foreground)]" x-text="editData.tahun_ajaran"></span>
                        </div>
                    </div>

                    <div class="rounded-lg border border-[var(--border)] bg-[var(--muted)]/40 p-4 space-y-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-[var(--muted-foreground)]">
                            Komponen Nilai (0 – 100)
                        </p>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-[var(--foreground)]">Tugas</label>
                                <input type="number" name="nilai_tugas" min="0" max="100" step="0.01" required
                                       x-model="editData.nilai_tugas" @input="hitungEditNilaiAkhir()"
                                       class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                              px-3 text-sm text-center tabular-nums outline-none transition
                                              focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-[var(--foreground)]">UTS</label>
                                <input type="number" name="nilai_uts" min="0" max="100" step="0.01" required
                                       x-model="editData.nilai_uts" @input="hitungEditNilaiAkhir()"
                                       class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                              px-3 text-sm text-center tabular-nums outline-none transition
                                              focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-xs font-medium text-[var(--foreground)]">UAS</label>
                                <input type="number" name="nilai_uas" min="0" max="100" step="0.01" required
                                       x-model="editData.nilai_uas" @input="hitungEditNilaiAkhir()"
                                       class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                              px-3 text-sm text-center tabular-nums outline-none transition
                                              focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                            </div>
                        </div>

                        <div class="flex items-center justify-between rounded-md bg-[var(--card)]
                                    border border-[var(--border)] px-4 py-2.5">
                            <span class="text-sm text-[var(--muted-foreground)]">Nilai Akhir</span>
                            <div class="flex items-center gap-3">
                                <span class="font-display text-xl font-bold text-[var(--foreground)] tabular-nums"
                                      x-text="editNilaiAkhir?.toFixed(1)"></span>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs
                                             font-medium ring-1 ring-inset"
                                      :class="editGradeClass"
                                      x-text="editGrade + ' — ' + editPredikat"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 border-t border-[var(--border)] px-5 py-4">
                    <x-ui.button variant="secondary" type="button" @click="modalEditOpen = false">Batal</x-ui.button>
                    <x-ui.button type="submit">Simpan Perubahan</x-ui.button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
function nilaiApp() {
    return {
        modalTambah: {{ $errors->any() ? 'true' : 'false' }},
        modalEditOpen: false,
        siswaList: @json($siswaList),

        form: {
            siswa_id: '',
            mapel_id: '',
            semester: '{{ $semester }}',
            tahun_ajaran: '{{ $tahunAjaran }}',
            nilai_tugas: '',
            nilai_uts: '',
            nilai_uas: '',
        },

        nilaiAkhir: null,
        grade: '',
        predikat: '',
        gradeClass: '',

        editData: {},
        editNilaiAkhir: null,
        editGrade: '',
        editPredikat: '',
        editGradeClass: '',

        init() {
            @if($kelasId)
                this.loadSiswa({{ $kelasId }});
            @endif
        },

        async loadSiswa(kelasId) {
            if (!kelasId) { this.siswaList = []; return; }
            try {
                const res = await fetch(`/admin/api/siswa-by-kelas/${kelasId}`);
                this.siswaList = await res.json();
                this.form.siswa_id = '';
            } catch (e) {
                console.error(e);
            }
        },

        hitungNilaiAkhir() {
            const t  = parseFloat(this.form.nilai_tugas) || 0;
            const ut = parseFloat(this.form.nilai_uts)   || 0;
            const ua = parseFloat(this.form.nilai_uas)   || 0;

            if (this.form.nilai_tugas === '' && this.form.nilai_uts === '' && this.form.nilai_uas === '') {
                this.nilaiAkhir = null; return;
            }

            this.nilaiAkhir = (t + ut + ua) / 3;
            this.grade      = this.getGrade(this.nilaiAkhir);
            this.predikat   = this.getPredikat(this.grade);
            this.gradeClass = this.getGradeClass(this.grade);
        },

        hitungEditNilaiAkhir() {
            const t  = parseFloat(this.editData.nilai_tugas) || 0;
            const ut = parseFloat(this.editData.nilai_uts)   || 0;
            const ua = parseFloat(this.editData.nilai_uas)   || 0;

            this.editNilaiAkhir = (t + ut + ua) / 3;
            this.editGrade      = this.getGrade(this.editNilaiAkhir);
            this.editPredikat   = this.getPredikat(this.editGrade);
            this.editGradeClass = this.getGradeClass(this.editGrade);
        },

        openEdit(data) {
            this.editData       = { ...data };
            this.editNilaiAkhir = (parseFloat(data.nilai_tugas) + parseFloat(data.nilai_uts) + parseFloat(data.nilai_uas)) / 3;
            this.editGrade      = this.getGrade(this.editNilaiAkhir);
            this.editPredikat   = this.getPredikat(this.editGrade);
            this.editGradeClass = this.getGradeClass(this.editGrade);
            this.modalEditOpen  = true;
        },

        getGrade(nilai) {
            if (nilai >= 90) return 'A';
            if (nilai >= 80) return 'B';
            if (nilai >= 70) return 'C';
            if (nilai >= 60) return 'D';
            return 'E';
        },

        getPredikat(grade) {
            const map = { A: 'Sangat Baik', B: 'Baik', C: 'Cukup', D: 'Kurang', E: 'Sangat Kurang' };
            return map[grade] ?? '';
        },

        getGradeClass(grade) {
            const map = {
                A: 'bg-green-50 text-green-700 ring-green-200',
                B: 'bg-blue-50 text-blue-700 ring-blue-200',
                C: 'bg-yellow-50 text-yellow-700 ring-yellow-200',
                D: 'bg-orange-50 text-orange-700 ring-orange-200',
                E: 'bg-red-50 text-red-700 ring-red-200',
            };
            return map[grade] ?? 'bg-gray-50 text-gray-700 ring-gray-200';
        },
    }
}
</script>
@endpush
