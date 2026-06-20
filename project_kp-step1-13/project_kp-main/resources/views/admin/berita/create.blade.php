@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('breadcrumb')
    <a href="{{ route('admin.berita.index') }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
        Berita & Pengumuman
    </a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Tambah</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Tambah Berita"
        desc="Buat artikel berita atau pengumuman baru untuk halaman publik sekolah."
        :back="route('admin.berita.index')"
    />

    <form method="POST" action="{{ route('admin.berita.store') }}"
          enctype="multipart/form-data" x-data="{ preview: null }">
        @csrf

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Kolom kanan: Settings --}}
            <div class="order-1 lg:order-2 space-y-6">

                <x-ui.section-card title="Pengaturan Publikasi">
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-[var(--foreground)]">
                                Kategori <span class="text-[var(--destructive)]">*</span>
                            </label>
                            <select name="kategori" required
                                    class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                           px-3 text-sm outline-none transition text-[var(--foreground)]
                                           focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                                <option value="berita"     {{ old('kategori') === 'berita'     ? 'selected' : '' }}>Berita</option>
                                <option value="pengumuman" {{ old('kategori') === 'pengumuman' ? 'selected' : '' }}>Pengumuman</option>
                            </select>
                            @error('kategori')
                                <p class="text-xs text-[var(--destructive)]">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Toggle publish --}}
                        <div class="flex items-center justify-between rounded-lg border
                                    border-[var(--border)] px-4 py-3" x-data="{ pub: {{ old('is_published', 1) ? 'true' : 'false' }} }">
                            <div>
                                <p class="text-sm font-medium text-[var(--foreground)]">Publikasikan</p>
                                <p class="text-xs text-[var(--muted-foreground)]"
                                   x-text="pub ? 'Tampil di halaman publik' : 'Disimpan sebagai draft'"></p>
                            </div>
                            <button type="button" @click="pub = !pub"
                                    :class="pub ? 'bg-[var(--primary)]' : 'bg-[var(--muted)]'"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full
                                           border-2 border-transparent transition-colors duration-200">
                                <span :class="pub ? 'translate-x-5' : 'translate-x-0'"
                                      class="pointer-events-none inline-block h-5 w-5 rounded-full bg-white
                                             shadow-sm transition-transform duration-200"></span>
                            </button>
                            <input type="hidden" name="is_published" :value="pub ? '1' : '0'">
                        </div>
                    </div>
                </x-ui.section-card>

                {{-- Upload gambar --}}
                <x-ui.section-card title="Gambar Utama">
                    <div class="space-y-3">
                        <div class="aspect-video w-full overflow-hidden rounded-lg border border-dashed
                                    border-[var(--border)] bg-[var(--muted)] flex items-center justify-center">
                            <img x-show="preview" :src="preview"
                                 class="h-full w-full object-cover rounded-lg">
                            <div x-show="!preview" class="text-center p-4">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="mx-auto h-8 w-8 text-[var(--muted-foreground)] mb-2"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z"/>
                                </svg>
                                <p class="text-xs text-[var(--muted-foreground)]">Belum ada gambar</p>
                            </div>
                        </div>

                        <label class="flex w-full cursor-pointer items-center justify-center gap-2 rounded-md
                                      border border-[var(--border)] bg-[var(--card)] px-4 py-2 text-sm
                                      font-medium text-[var(--foreground)] hover:bg-[var(--muted)] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                            </svg>
                            Pilih Gambar
                            <input type="file" name="gambar" accept="image/*" class="hidden"
                                   @change="preview = $event.target.files[0]
                                       ? URL.createObjectURL($event.target.files[0]) : null">
                        </label>
                        <p class="text-center text-xs text-[var(--muted-foreground)]">
                            JPG, PNG, WebP. Maks. 2MB.
                        </p>
                        @error('gambar')
                            <p class="text-xs text-[var(--destructive)]">{{ $message }}</p>
                        @enderror
                    </div>
                </x-ui.section-card>

                <x-ui.button type="submit" class="w-full justify-center">
                    Simpan Berita
                </x-ui.button>
            </div>

            {{-- Kolom kiri: Konten --}}
            <div class="order-2 lg:order-1 lg:col-span-2 space-y-5">
                <x-ui.section-card title="Konten Berita">
                    <div class="space-y-5">
                        <x-ui.input
                            name="judul"
                            label="Judul"
                            :required="true"
                            placeholder="Judul berita atau pengumuman..."
                        />

                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-[var(--foreground)]">
                                Konten <span class="text-[var(--destructive)]">*</span>
                            </label>
                            <textarea name="konten" rows="16" required
                                      class="w-full rounded-md border border-[var(--input)] bg-[var(--card)]
                                             px-3 py-2.5 text-sm outline-none transition resize-y
                                             text-[var(--foreground)] placeholder:text-[var(--muted-foreground)]
                                             focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20
                                             {{ $errors->has('konten') ? 'border-red-400 bg-red-50' : '' }}"
                                      placeholder="Tulis konten berita di sini...">{{ old('konten') }}</textarea>
                            @error('konten')
                                <p class="text-xs text-[var(--destructive)]">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </x-ui.section-card>
            </div>
        </div>
    </form>

@endsection
