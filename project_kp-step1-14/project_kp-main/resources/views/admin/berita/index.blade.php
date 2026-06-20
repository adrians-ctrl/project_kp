@extends('layouts.admin')

@section('title', 'Berita & Pengumuman')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Berita & Pengumuman</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Berita & Pengumuman"
        desc="Kelola konten berita dan pengumuman yang ditampilkan di halaman publik sekolah."
    >
        <x-slot:actions>
            <x-ui.button href="{{ route('admin.berita.create') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Berita
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Filter --}}
    <x-ui.section-card>
        <form method="GET" action="{{ route('admin.berita.index') }}">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Cari</label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-[var(--muted-foreground)]"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        <input type="text" name="search" value="{{ $keyword }}"
                               placeholder="Cari judul berita..."
                               class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                      pl-9 pr-3 text-sm outline-none transition text-[var(--foreground)]
                                      placeholder:text-[var(--muted-foreground)]
                                      focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                    </div>
                </div>
                <div class="sm:w-40">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Kategori</label>
                    <select name="kategori"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="">Semua</option>
                        <option value="berita"      {{ $kategori === 'berita'      ? 'selected' : '' }}>Berita</option>
                        <option value="pengumuman"  {{ $kategori === 'pengumuman'  ? 'selected' : '' }}>Pengumuman</option>
                    </select>
                </div>
                <div class="sm:w-40">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Status</label>
                    <select name="status"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="">Semua</option>
                        <option value="1" {{ $status === '1' ? 'selected' : '' }}>Dipublikasikan</option>
                        <option value="0" {{ $status === '0' ? 'selected' : '' }}>Disembunyikan</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <x-ui.button type="submit">Cari</x-ui.button>
                    @if ($keyword || $kategori || $status !== null && $status !== '')
                        <x-ui.button variant="secondary" href="{{ route('admin.berita.index') }}">Reset</x-ui.button>
                    @endif
                </div>
            </div>
        </form>
    </x-ui.section-card>

    {{-- Tabel --}}
    <x-ui.section-card :noPadding="true">
        <x-slot:actions>
            <span class="text-xs text-[var(--muted-foreground)]">{{ $berita->total() }} artikel</span>
        </x-slot:actions>

        @if ($berita->isEmpty())
            <x-ui.empty-state
                title="Belum ada berita"
                message="Tambahkan berita atau pengumuman untuk ditampilkan di halaman publik."
            >
                <x-ui.button href="{{ route('admin.berita.create') }}" size="sm">
                    Tambah Berita
                </x-ui.button>
            </x-ui.empty-state>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">No</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Judul</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Kategori</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Status</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Penulis</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Tanggal</th>
                            <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        @foreach ($berita as $i => $item)
                            <tr class="hover:bg-[var(--muted)]/40 transition-colors"
                                x-data="{ modalHapus: false }">
                                <td class="px-5 py-3.5 tabular-nums text-[var(--muted-foreground)]">
                                    {{ $berita->firstItem() + $i }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-start gap-3">
                                        {{-- Thumbnail --}}
                                        @if ($item->gambar_url)
                                            <img src="{{ $item->gambar_url }}"
                                                 alt="{{ $item->judul }}"
                                                 class="h-10 w-16 shrink-0 rounded-md object-cover ring-1 ring-[var(--border)]">
                                        @else
                                            <div class="h-10 w-16 shrink-0 rounded-md bg-[var(--muted)]
                                                        flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[var(--muted-foreground)]"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="font-medium text-[var(--foreground)] line-clamp-1">
                                                {{ $item->judul }}
                                            </p>
                                            <p class="text-xs text-[var(--muted-foreground)] mt-0.5 line-clamp-1">
                                                {{ $item->konten_ringkas }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <x-ui.badge :tone="$item->kategori === 'pengumuman' ? 'warning' : 'info'">
                                        {{ ucfirst($item->kategori) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    {{-- Toggle publish --}}
                                    <form method="POST"
                                          action="{{ route('admin.berita.toggle-publish', $item) }}">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs
                                                       font-medium ring-1 ring-inset transition-colors cursor-pointer
                                                       {{ $item->is_published
                                                          ? 'bg-green-50 text-green-700 ring-green-200 hover:bg-green-100'
                                                          : 'bg-[var(--muted)] text-[var(--muted-foreground)] ring-[var(--border)] hover:bg-[var(--muted-foreground)]/10' }}"
                                                title="{{ $item->is_published ? 'Klik untuk menyembunyikan' : 'Klik untuk mempublikasikan' }}">
                                            <span class="h-1.5 w-1.5 rounded-full
                                                         {{ $item->is_published ? 'bg-green-500' : 'bg-[var(--muted-foreground)]' }}"></span>
                                            {{ $item->is_published ? 'Publik' : 'Draft' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                    {{ $item->user?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                    {{ $item->created_at->diffForHumans() }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.berita.edit', $item) }}"
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
                                        :action="route('admin.berita.destroy', $item)"
                                        :label="$item->judul"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($berita->hasPages())
                <div class="border-t border-[var(--border)] px-5 py-4">
                    {{ $berita->links('components.ui.pagination') }}
                </div>
            @endif
        @endif
    </x-ui.section-card>

@endsection
