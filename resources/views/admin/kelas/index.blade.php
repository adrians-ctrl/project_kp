@extends('layouts.admin')

@section('title', 'Kelola Kelas')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Kelas</span>
@endsection

@section('content')

    <div x-data="{ modalTambah: false }">

        {{-- Page Header --}}
        <x-ui.page-header
            title="Kelola Kelas"
            desc="Manajemen data kelas, tingkat, tahun ajaran, dan wali kelas."
        >
            <x-slot:actions>
                <x-ui.button @click="modalTambah = true">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Tambah Kelas
                </x-ui.button>
            </x-slot:actions>
        </x-ui.page-header>

        {{-- Tabel --}}
        <x-ui.section-card :noPadding="true">
            <x-slot:actions>
                <span class="text-xs text-[var(--muted-foreground)]">
                    {{ $kelas->total() }} kelas ditemukan
                </span>
            </x-slot:actions>

            @if ($kelas->isEmpty())
                <x-ui.empty-state
                    title="Belum ada data kelas"
                    message="Mulai dengan menambahkan kelas pertama."
                >
                    <x-ui.button @click="modalTambah = true" size="sm">
                        Tambah Kelas
                    </x-ui.button>
                </x-ui.empty-state>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">
                                    No
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">
                                    Nama Kelas
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">
                                    Tingkat
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">
                                    Tahun Ajaran
                                </th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">
                                    Wali Kelas
                                </th>
                                <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">
                                    Jumlah Siswa
                                </th>
                                <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)]">
                            @foreach ($kelas as $i => $item)
                                <tr class="hover:bg-[var(--muted)]/40 transition-colors"
                                    x-data="{ modalHapus: false }">
                                    <td class="px-5 py-3.5 tabular-nums text-[var(--muted-foreground)]">
                                        {{ $kelas->firstItem() + $i }}
                                    </td>
                                    <td class="px-5 py-3.5 font-medium text-[var(--foreground)]">
                                        {{ $item->nama_kelas }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <x-ui.badge tone="info">
                                            Tingkat {{ $item->tingkat }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                        {{ $item->tahun_ajaran }}
                                    </td>
                                    <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                        {{ $item->waliKelas?->nama_lengkap ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="inline-flex h-6 min-w-[1.5rem] items-center justify-center
                                                      rounded-full bg-[var(--primary-soft)] px-2
                                                      text-xs font-semibold text-[var(--primary)]">
                                            {{ $item->siswa_count }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center justify-end gap-1">
                                            {{-- Edit --}}
                                            <a href="{{ route('admin.kelas.edit', $item) }}"
                                               class="inline-flex h-8 w-8 items-center justify-center
                                                      rounded-md text-[var(--muted-foreground)]
                                                      hover:bg-[var(--muted)] hover:text-[var(--foreground)]
                                                      transition-colors"
                                               title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                     fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                                </svg>
                                            </a>

                                            {{-- Hapus --}}
                                            <button
                                                @click="modalHapus = true"
                                                type="button"
                                                class="inline-flex h-8 w-8 items-center justify-center
                                                       rounded-md text-[var(--muted-foreground)]
                                                       hover:bg-[var(--destructive)]/10
                                                       hover:text-[var(--destructive)]
                                                       transition-colors"
                                                title="Hapus"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                     fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- Modal konfirmasi hapus per baris --}}
                                        <x-ui.confirm-delete
                                            name="modalHapus"
                                            :action="route('admin.kelas.destroy', $item)"
                                            :label="$item->nama_kelas"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($kelas->hasPages())
                    <div class="border-t border-[var(--border)] px-5 py-4">
                        {{ $kelas->links('components.ui.pagination') }}
                    </div>
                @endif
            @endif
        </x-ui.section-card>

        {{-- Modal Tambah Kelas --}}
        <div
            x-show="modalTambah"
            x-transition:enter="transition-opacity duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="modalTambah = false"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="display: none"
        >
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                 @click="modalTambah = false"></div>

            <div
                x-show="modalTambah"
                x-transition:enter="transition duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="relative w-full max-w-md rounded-xl border border-[var(--border)]
                       bg-[var(--card)] shadow-[var(--shadow-elevated)]"
            >
                <div class="flex items-center justify-between border-b
                            border-[var(--border)] px-5 py-4">
                    <h2 class="font-display text-base font-semibold text-[var(--foreground)]">
                        Tambah Kelas
                    </h2>
                    <button @click="modalTambah = false" type="button"
                            class="flex h-8 w-8 items-center justify-center rounded-md
                                   text-[var(--muted-foreground)] hover:bg-[var(--muted)]
                                   transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.kelas.store') }}">
                    @csrf
                    <div class="space-y-4 px-5 py-5">
                        <x-ui.input
                            name="nama_kelas"
                            label="Nama Kelas"
                            placeholder="Contoh: Kelas 1A"
                            :required="true"
                        />
                        <div class="grid grid-cols-2 gap-4">
                            <x-ui.input
                                name="tingkat"
                                label="Tingkat"
                                placeholder="Contoh: 1"
                                :required="true"
                            />
                            <x-ui.input
                                name="tahun_ajaran"
                                label="Tahun Ajaran"
                                placeholder="Contoh: 2025/2026"
                                :required="true"
                            />
                        </div>
                        <x-ui.select
                            name="wali_kelas_id"
                            label="Wali Kelas"
                            :options="$guruList->pluck('nama_lengkap', 'id')"
                            placeholder="Pilih wali kelas (opsional)"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t
                                border-[var(--border)] px-5 py-4">
                        <x-ui.button variant="secondary" type="button"
                                     @click="modalTambah = false">
                            Batal
                        </x-ui.button>
                        <x-ui.button type="submit">
                            Simpan
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>

    </div>

@endsection
