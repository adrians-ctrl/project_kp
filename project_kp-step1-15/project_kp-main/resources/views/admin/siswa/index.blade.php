@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Siswa</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Data Siswa"
        desc="Manajemen data peserta didik seluruh kelas."
    >
        <x-slot:actions>
            {{-- Export --}}
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
                    <a href="{{ route('admin.siswa.export.pdf', request()->only(['search','kelas_id','jenis_kelamin'])) }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-sm text-[var(--foreground)]
                              hover:bg-[var(--muted)] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        Ekspor PDF
                    </a>
                    <a href="{{ route('admin.siswa.export.excel', request()->only(['search','kelas_id','jenis_kelamin'])) }}"
                       class="flex items-center gap-2.5 px-4 py-2 text-sm text-[var(--foreground)]
                              hover:bg-[var(--muted)] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375"/>
                        </svg>
                        Ekspor Excel
                    </a>
                </div>
            </div>

            <x-ui.button href="{{ route('admin.siswa.create') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Siswa
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Filter & Search --}}
    <x-ui.section-card>
        <form method="GET" action="{{ route('admin.siswa.index') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">

                {{-- Search --}}
                <div class="flex-1">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">
                        Cari Siswa
                    </label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-[var(--muted-foreground)]"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $keyword }}"
                               placeholder="Nama, NISN, atau NIS..."
                               class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                      pl-9 pr-3 text-sm outline-none transition text-[var(--foreground)]
                                      placeholder:text-[var(--muted-foreground)]
                                      focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                    </div>
                </div>

                {{-- Filter Kelas --}}
                <div class="sm:w-48">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">
                        Kelas
                    </label>
                    <select name="kelas_id"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}"
                                    {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter JK --}}
                <div class="sm:w-44">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">
                        Jenis Kelamin
                    </label>
                    <select name="jenis_kelamin"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="">Semua</option>
                        <option value="L" {{ $jk === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ $jk === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <div class="flex gap-2">
                    <x-ui.button type="submit">Cari</x-ui.button>
                    @if ($keyword || $kelasId || $jk)
                        <x-ui.button variant="secondary" href="{{ route('admin.siswa.index') }}">
                            Reset
                        </x-ui.button>
                    @endif
                </div>
            </div>
        </form>
    </x-ui.section-card>

    {{-- Tabel --}}
    <x-ui.section-card :noPadding="true">
        <x-slot:actions>
            <span class="text-xs text-[var(--muted-foreground)]">
                {{ $siswa->total() }} siswa ditemukan
            </span>
        </x-slot:actions>

        @if ($siswa->isEmpty())
            <x-ui.empty-state
                title="Tidak ada data siswa"
                :message="$keyword || $kelasId || $jk
                    ? 'Tidak ditemukan siswa yang sesuai dengan filter.'
                    : 'Mulai dengan menambahkan data siswa pertama.'"
            >
                @if (!$keyword && !$kelasId && !$jk)
                    <x-ui.button href="{{ route('admin.siswa.create') }}" size="sm">
                        Tambah Siswa
                    </x-ui.button>
                @endif
            </x-ui.empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">No</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Siswa</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">NISN / NIS</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Kelas</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">JK</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Orang Tua</th>
                            <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        @foreach ($siswa as $i => $item)
                            <tr class="hover:bg-[var(--muted)]/40 transition-colors"
                                x-data="{ modalHapus: false }">
                                <td class="px-5 py-3.5 tabular-nums text-[var(--muted-foreground)]">
                                    {{ $siswa->firstItem() + $i }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->foto_url }}"
                                             alt="{{ $item->nama_lengkap }}"
                                             class="h-8 w-8 rounded-full object-cover ring-1 ring-[var(--border)] shrink-0">
                                        <div>
                                            <a href="{{ route('admin.siswa.show', $item) }}"
                                               class="font-medium text-[var(--foreground)] hover:text-[var(--primary)] transition-colors">
                                                {{ $item->nama_lengkap }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-mono text-xs text-[var(--foreground)]">{{ $item->nisn }}</div>
                                    <div class="font-mono text-xs text-[var(--muted-foreground)]">{{ $item->nis }}</div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <x-ui.badge tone="info">{{ $item->kelas->nama_kelas ?? '—' }}</x-ui.badge>
                                </td>
                                <td class="px-5 py-3.5">
                                    <x-ui.badge :tone="$item->jenis_kelamin === 'L' ? 'info' : 'neutral'">
                                        {{ $item->jenis_kelamin }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                    {{ $item->nama_orang_tua ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.siswa.show', $item) }}"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-md
                                                  text-[var(--muted-foreground)] hover:bg-[var(--muted)]
                                                  hover:text-[var(--foreground)] transition-colors" title="Detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.siswa.edit', $item) }}"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-md
                                                  text-[var(--muted-foreground)] hover:bg-[var(--muted)]
                                                  hover:text-[var(--foreground)] transition-colors" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                            </svg>
                                        </a>
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
                                        :action="route('admin.siswa.destroy', $item)"
                                        :label="$item->nama_lengkap"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($siswa->hasPages())
                <div class="border-t border-[var(--border)] px-5 py-4">
                    {{ $siswa->links('components.ui.pagination') }}
                </div>
            @endif
        @endif
    </x-ui.section-card>

@endsection
