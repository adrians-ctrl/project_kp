@extends('layouts.admin')

@section('title', 'Guru & Staf')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Guru & Staf</span>
@endsection

@section('content')

<div x-data="{ view: localStorage.getItem('guruView') || 'grid' }"
     x-init="$watch('view', v => localStorage.setItem('guruView', v))">

    {{-- Page Header --}}
    <x-ui.page-header
        title="Guru & Staf"
        desc="Manajemen data tenaga pengajar dan staf administrasi sekolah."
    >
        <x-slot:actions>
            {{-- Toggle view --}}
            <div class="flex items-center rounded-md border border-[var(--border)]
                        bg-[var(--card)] p-0.5">
                <button
                    @click="view = 'grid'"
                    type="button"
                    :class="view === 'grid'
                        ? 'bg-[var(--primary)] text-[var(--primary-foreground)]'
                        : 'text-[var(--muted-foreground)] hover:text-[var(--foreground)]'"
                    class="flex h-7 w-7 items-center justify-center rounded transition-colors"
                    title="Tampilan kartu"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25
                                 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25
                                 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0
                                 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1
                                 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25
                                 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25
                                 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0
                                 1 13.5 18v-2.25Z"/>
                    </svg>
                </button>
                <button
                    @click="view = 'table'"
                    type="button"
                    :class="view === 'table'
                        ? 'bg-[var(--primary)] text-[var(--primary-foreground)]'
                        : 'text-[var(--muted-foreground)] hover:text-[var(--foreground)]'"
                    class="flex h-7 w-7 items-center justify-center rounded transition-colors"
                    title="Tampilan tabel"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375
                                 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375
                                 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375
                                 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                    </svg>
                </button>
            </div>

            <x-ui.button href="{{ route('admin.guru.create') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Guru
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    @if ($guru->isEmpty())
        <x-ui.section-card>
            <x-ui.empty-state
                title="Belum ada data guru atau staf"
                message="Tambahkan data tenaga pengajar dan staf administrasi sekolah."
            >
                <x-ui.button href="{{ route('admin.guru.create') }}" size="sm">
                    Tambah Guru
                </x-ui.button>
            </x-ui.empty-state>
        </x-ui.section-card>
    @else

        {{-- ============================================================
             GRID VIEW
        ============================================================= --}}
        <div x-show="view === 'grid'"
             class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($guru as $item)
                <div class="group relative flex flex-col rounded-xl border border-[var(--border)]
                            bg-[var(--card)] p-5 shadow-[var(--shadow-card)]
                            transition-shadow hover:shadow-[var(--shadow-elevated)]"
                     x-data="{ modalHapus: false }">

                    {{-- Foto & nama --}}
                    <div class="flex items-start gap-3 mb-4">
                        <div class="relative h-12 w-12 shrink-0">
                            <img
                                src="{{ $item->foto_url }}"
                                alt="{{ $item->nama_lengkap }}"
                                class="h-12 w-12 rounded-full object-cover ring-2 ring-[var(--border)]"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($item->nama_lengkap) }}&background=1e3a8a&color=fff&size=48'"
                            >
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-semibold text-[var(--foreground)]
                                       leading-tight">
                                {{ $item->nama_lengkap }}
                            </h3>
                            <p class="truncate text-xs text-[var(--muted-foreground)] mt-0.5">
                                {{ $item->jabatan }}
                            </p>
                        </div>
                    </div>

                    {{-- Detail --}}
                    <div class="space-y-1.5 flex-1">
                        @if ($item->mapel)
                            <div class="flex items-center gap-2 text-xs text-[var(--muted-foreground)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3
                                             .512v14.25A8.987 8.987 0 0 0 6 18c2.305 0 4.408.867 6
                                             2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18
                                             3 .512v14.25A8.987 8.987 0 0 1 18 18a8.967 8.967 0 0
                                             0-6 2.292m0-14.25v14.25"/>
                                </svg>
                                <span class="truncate">{{ $item->mapel }}</span>
                            </div>
                        @endif

                        @if ($item->nip)
                            <div class="flex items-center gap-2 text-xs text-[var(--muted-foreground)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0
                                             0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0
                                             0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875
                                             1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721
                                             6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376
                                             0 0 1 6.338 0Z"/>
                                </svg>
                                <span class="font-mono truncate">{{ $item->nip }}</span>
                            </div>
                        @endif

                        @if ($item->no_hp)
                            <div class="flex items-center gap-2 text-xs text-[var(--muted-foreground)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0
                                             2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11
                                             -.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035
                                             12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363
                                             -.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25
                                             2.25 0 0 0 2.25 4.5v2.25Z"/>
                                </svg>
                                <span>{{ $item->no_hp }}</span>
                            </div>
                        @endif

                        @if ($item->kelas->isNotEmpty())
                            <div class="flex items-center gap-2 text-xs text-[var(--muted-foreground)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75
                                             3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0
                                             -.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                                <span>Wali: {{ $item->kelas->pluck('nama_kelas')->join(', ') }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="mt-4 flex items-center justify-between border-t
                                border-[var(--border)] pt-4">
                        <x-ui.badge :tone="$item->jabatan === 'Kepala Sekolah' ? 'warning' : ($item->mapel ? 'info' : 'neutral')">
                            {{ $item->jabatan === 'Guru Kelas' || str_starts_with($item->jabatan, 'Guru') ? 'Guru' : 'Staf' }}
                        </x-ui.badge>

                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.guru.edit', $item) }}"
                               class="inline-flex h-7 w-7 items-center justify-center rounded-md
                                      text-[var(--muted-foreground)] hover:bg-[var(--muted)]
                                      hover:text-[var(--foreground)] transition-colors"
                               title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582
                                             16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1
                                             1.13-1.897l8.932-8.931Z"/>
                                </svg>
                            </a>
                            <button
                                @click="modalHapus = true"
                                type="button"
                                class="inline-flex h-7 w-7 items-center justify-center rounded-md
                                       text-[var(--muted-foreground)] hover:bg-[var(--destructive)]/10
                                       hover:text-[var(--destructive)] transition-colors"
                                title="Hapus"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107
                                             1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244
                                             2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456
                                             0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114
                                             1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0
                                             -1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18
                                             .037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <x-ui.confirm-delete
                        name="modalHapus"
                        :action="route('admin.guru.destroy', $item)"
                        :label="$item->nama_lengkap"
                    />
                </div>
            @endforeach
        </div>

        {{-- ============================================================
             TABLE VIEW
        ============================================================= --}}
        <div x-show="view === 'table'">
            <x-ui.section-card :noPadding="true">
                <x-slot:actions>
                    <span class="text-xs text-[var(--muted-foreground)]">
                        {{ $guru->total() }} orang
                    </span>
                </x-slot:actions>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">No</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nama</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">NIP</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Jabatan</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Mata Pelajaran</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">No. HP</th>
                                <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Wali Kelas</th>
                                <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)]">
                            @foreach ($guru as $i => $item)
                                <tr class="hover:bg-[var(--muted)]/40 transition-colors"
                                    x-data="{ modalHapus: false }">
                                    <td class="px-5 py-3.5 tabular-nums text-[var(--muted-foreground)]">
                                        {{ $guru->firstItem() + $i }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <img
                                                src="{{ $item->foto_url }}"
                                                alt="{{ $item->nama_lengkap }}"
                                                class="h-8 w-8 rounded-full object-cover ring-1 ring-[var(--border)]"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($item->nama_lengkap) }}&background=1e3a8a&color=fff&size=32'"
                                            >
                                            <span class="font-medium text-[var(--foreground)]">
                                                {{ $item->nama_lengkap }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 font-mono text-xs text-[var(--muted-foreground)]">
                                        {{ $item->nip ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                        {{ $item->jabatan }}
                                    </td>
                                    <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                        {{ $item->mapel ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                        {{ $item->no_hp ?? '—' }}
                                    </td>
                                    <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                        {{ $item->kelas->isNotEmpty() ? $item->kelas->pluck('nama_kelas')->join(', ') : '—' }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('admin.guru.edit', $item) }}"
                                               class="inline-flex h-8 w-8 items-center justify-center
                                                      rounded-md text-[var(--muted-foreground)]
                                                      hover:bg-[var(--muted)] hover:text-[var(--foreground)]
                                                      transition-colors"
                                               title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                                </svg>
                                            </a>
                                            <button @click="modalHapus = true" type="button"
                                                    class="inline-flex h-8 w-8 items-center justify-center
                                                           rounded-md text-[var(--muted-foreground)]
                                                           hover:bg-[var(--destructive)]/10
                                                           hover:text-[var(--destructive)] transition-colors"
                                                    title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <x-ui.confirm-delete
                                            name="modalHapus"
                                            :action="route('admin.guru.destroy', $item)"
                                            :label="$item->nama_lengkap"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($guru->hasPages())
                    <div class="border-t border-[var(--border)] px-5 py-4">
                        {{ $guru->links('components.ui.pagination') }}
                    </div>
                @endif
            </x-ui.section-card>
        </div>

        {{-- Pagination untuk grid view --}}
        <div x-show="view === 'grid'">
            @if ($guru->hasPages())
                <x-ui.section-card>
                    {{ $guru->links('components.ui.pagination') }}
                </x-ui.section-card>
            @endif
        </div>

    @endif
</div>

@endsection
