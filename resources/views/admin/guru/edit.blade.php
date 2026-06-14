@extends('layouts.admin')

@section('title', 'Edit Guru & Staf')

@section('breadcrumb')
    <a href="{{ route('admin.guru.index') }}" class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">Guru & Staf</a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Edit</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Edit Guru & Staf"
        desc="Perbarui data {{ $guru->nama_lengkap }}."
        :back="route('admin.guru.index')"
    />

    <x-ui.section-card title="Data Guru / Staf" class="max-w-2xl">
        <form method="POST" action="{{ route('admin.guru.update', $guru) }}"
              enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <x-ui.input name="nama_lengkap" label="Nama Lengkap"
                    placeholder="Contoh: Budi Santoso, S.Pd." :required="true"
                    :value="old('nama_lengkap', $guru->nama_lengkap)" />
                <x-ui.input name="nip" label="NIP" placeholder="Nomor Induk Pegawai (opsional)"
                    :value="old('nip', $guru->nip)" />
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-[var(--foreground)]">
                        Jabatan <span class="text-[var(--destructive)] ml-0.5">*</span>
                    </label>
                    <select name="jabatan" required
                            class="w-full h-9 rounded-md border px-3 text-sm outline-none transition
                                   bg-[var(--card)] text-[var(--foreground)] border-[var(--input)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="">Pilih jabatan...</option>
                        @foreach (['Kepala Sekolah','Guru Kelas','Guru PAI','Guru PJOK','Guru Bahasa Inggris','Guru Seni Budaya','Tata Usaha','Operator Sekolah','Penjaga Sekolah'] as $j)
                            <option value="{{ $j }}" {{ old('jabatan', $guru->jabatan) === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                    @error('jabatan')<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
                </div>
                <x-ui.input name="mapel" label="Mata Pelajaran Diampu"
                    placeholder="Contoh: Matematika (opsional)"
                    :value="old('mapel', $guru->mapel)" />
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="space-y-1.5">
                    <label class="block text-sm font-medium text-[var(--foreground)]">Pendidikan Terakhir</label>
                    <select name="pendidikan"
                            class="w-full h-9 rounded-md border px-3 text-sm outline-none transition
                                   bg-[var(--card)] text-[var(--foreground)] border-[var(--input)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="">Pilih pendidikan...</option>
                        @foreach (['SMA/SMK','D3','S1','S2','S3'] as $p)
                            <option value="{{ $p }}" {{ old('pendidikan', $guru->pendidikan) === $p ? 'selected' : '' }}>{{ $p }}</option>
                        @endforeach
                    </select>
                </div>
                <x-ui.input name="no_hp" label="Nomor HP" placeholder="Contoh: 08123456789"
                    :value="old('no_hp', $guru->no_hp)" />
            </div>

            {{-- Foto --}}
            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-[var(--foreground)]">Foto</label>
                <div x-data="{ preview: null }" class="flex items-start gap-4">
                    <div class="h-20 w-20 shrink-0 rounded-full border-2 border-[var(--border)] overflow-hidden">
                        <img :src="preview ?? '{{ $guru->foto_url }}'"
                             alt="{{ $guru->nama_lengkap }}"
                             class="h-full w-full object-cover"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($guru->nama_lengkap) }}&background=1e3a8a&color=fff&size=80'">
                    </div>
                    <div class="flex-1">
                        <input type="file" name="foto" accept="image/*"
                               @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null"
                               class="w-full text-sm text-[var(--muted-foreground)] file:mr-3 file:h-8 file:rounded-md
                                      file:border file:border-[var(--border)] file:bg-[var(--card)]
                                      file:px-3 file:text-xs file:font-medium file:text-[var(--foreground)]
                                      file:cursor-pointer hover:file:bg-[var(--muted)] file:transition-colors">
                        <p class="mt-1.5 text-xs text-[var(--muted-foreground)]">Kosongkan jika tidak ingin mengganti foto. Maks. 2MB.</p>
                        @error('foto')<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 border-t border-[var(--border)] pt-5">
                <x-ui.button type="submit">Simpan Perubahan</x-ui.button>
                <x-ui.button variant="secondary" href="{{ route('admin.guru.index') }}">Batal</x-ui.button>
            </div>
        </form>
    </x-ui.section-card>

@endsection
