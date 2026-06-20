@extends('layouts.admin')

@section('title', 'Galeri Sekolah')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Galeri</span>
@endsection

@section('content')

<div x-data="{ modalTambah: false, lightbox: null }">

    <x-ui.page-header
        title="Galeri Sekolah"
        desc="Kelola foto-foto kegiatan sekolah yang ditampilkan di halaman publik."
    >
        <x-slot:actions>
            <x-ui.button @click="modalTambah = true" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah Foto
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    @if ($galeri->isEmpty())
        <x-ui.section-card>
            <x-ui.empty-state
                title="Belum ada foto di galeri"
                message="Tambahkan foto kegiatan sekolah untuk ditampilkan di halaman publik."
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>'
            >
                <x-ui.button @click="modalTambah = true" size="sm" type="button">
                    Tambah Foto
                </x-ui.button>
            </x-ui.empty-state>
        </x-ui.section-card>
    @else
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">
            @foreach ($galeri as $item)
                <div class="group relative aspect-square overflow-hidden rounded-xl border
                            border-[var(--border)] bg-[var(--muted)]"
                     x-data="{ modalHapus: false }">
                    <img src="{{ $item->foto_url }}"
                         alt="{{ $item->judul }}"
                         class="h-full w-full object-cover transition-transform duration-300
                                group-hover:scale-105 cursor-pointer"
                         @click="lightbox = { url: '{{ $item->foto_url }}', judul: @js($item->judul), deskripsi: @js($item->deskripsi) }">

                    {{-- Overlay --}}
                    <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t
                                from-black/70 via-black/0 to-transparent opacity-0
                                group-hover:opacity-100 transition-opacity duration-200 p-3">
                        <p class="text-xs font-medium text-white line-clamp-2">{{ $item->judul }}</p>
                    </div>

                    {{-- Tombol hapus --}}
                    <button @click="modalHapus = true" type="button"
                            class="absolute top-2 right-2 flex h-7 w-7 items-center justify-center
                                   rounded-full bg-black/50 text-white opacity-0
                                   group-hover:opacity-100 transition-opacity hover:bg-red-600"
                            title="Hapus">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <x-ui.confirm-delete
                        name="modalHapus"
                        :action="route('admin.galeri.destroy', $item)"
                        :label="$item->judul"
                    />
                </div>
            @endforeach
        </div>

        @if ($galeri->hasPages())
            <x-ui.section-card>
                {{ $galeri->links('components.ui.pagination') }}
            </x-ui.section-card>
        @endif
    @endif

    {{-- Modal Tambah Foto --}}
    <div x-show="modalTambah"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="modalTambah = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="modalTambah = false"></div>
        <div x-show="modalTambah"
             x-transition:enter="transition duration-200"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-md rounded-xl border border-[var(--border)]
                    bg-[var(--card)] shadow-[var(--shadow-elevated)]"
             x-data="{ preview: null }">
            <div class="flex items-center justify-between border-b border-[var(--border)] px-5 py-4">
                <h2 class="font-display text-base font-semibold text-[var(--foreground)]">Tambah Foto Galeri</h2>
                <button @click="modalTambah = false" type="button"
                        class="flex h-8 w-8 items-center justify-center rounded-md
                               text-[var(--muted-foreground)] hover:bg-[var(--muted)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.galeri.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4 px-5 py-5">

                    {{-- Preview foto --}}
                    <div class="aspect-video w-full overflow-hidden rounded-lg border border-dashed
                                border-[var(--border)] bg-[var(--muted)] flex items-center justify-center">
                        <img x-show="preview" :src="preview" class="h-full w-full object-cover rounded-lg">
                        <div x-show="!preview" class="text-center p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-[var(--muted-foreground)] mb-2"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                            </svg>
                            <p class="text-xs text-[var(--muted-foreground)]">Pilih foto untuk diunggah</p>
                        </div>
                    </div>

                    <label class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-md
                                  border border-[var(--border)] bg-[var(--card)] px-4 py-2 text-sm
                                  font-medium text-[var(--foreground)] hover:bg-[var(--muted)] transition-colors">
                        Pilih Foto
                        <input type="file" name="foto" accept="image/*" required class="hidden"
                               @change="preview = $event.target.files[0]
                                   ? URL.createObjectURL($event.target.files[0]) : null">
                    </label>
                    @error('foto')<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror

                    <x-ui.input name="judul" label="Judul" :required="true"
                        placeholder="Contoh: Upacara Bendera 17 Agustus" />

                    <x-ui.textarea name="deskripsi" label="Deskripsi" :rows="2"
                        placeholder="Deskripsi singkat (opsional)" />
                </div>
                <div class="flex items-center justify-end gap-2 border-t border-[var(--border)] px-5 py-4">
                    <x-ui.button variant="secondary" type="button" @click="modalTambah = false">Batal</x-ui.button>
                    <x-ui.button type="submit">Unggah Foto</x-ui.button>
                </div>
            </form>
        </div>
    </div>

    {{-- Lightbox --}}
    <div x-show="lightbox"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="lightbox = null"
         class="fixed inset-0 z-50 flex items-center justify-center p-6"
         style="display:none">
        <div class="absolute inset-0 bg-black/80" @click="lightbox = null"></div>
        <div class="relative max-w-3xl w-full" @click.outside="lightbox = null">
            <img :src="lightbox?.url" :alt="lightbox?.judul"
                 class="w-full max-h-[75vh] object-contain rounded-lg">
            <div class="mt-3 text-center">
                <p class="text-white font-medium" x-text="lightbox?.judul"></p>
                <p x-show="lightbox?.deskripsi" class="text-white/70 text-sm mt-1" x-text="lightbox?.deskripsi"></p>
            </div>
            <button @click="lightbox = null" type="button"
                    class="absolute -top-3 -right-3 flex h-9 w-9 items-center justify-center
                           rounded-full bg-white text-[var(--foreground)] shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

</div>

@endsection
