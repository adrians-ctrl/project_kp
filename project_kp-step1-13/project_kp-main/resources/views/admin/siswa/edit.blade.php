@extends('layouts.admin')

@section('title', 'Edit Siswa')

@section('breadcrumb')
    <a href="{{ route('admin.siswa.index') }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">Siswa</a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <a href="{{ route('admin.siswa.show', $siswa) }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors truncate max-w-[160px]">
        {{ $siswa->nama_lengkap }}
    </a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Edit</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Edit Siswa"
        desc="Perbarui data {{ $siswa->nama_lengkap }}."
        :back="route('admin.siswa.show', $siswa)"
    />

    <form method="POST" action="{{ route('admin.siswa.update', $siswa) }}"
          enctype="multipart/form-data" x-data="{ preview: null }">
        @csrf
        @method('PUT')

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Foto --}}
            <x-ui.section-card title="Foto Siswa">
                <div class="flex flex-col items-center gap-4">
                    <div class="h-32 w-32 rounded-full overflow-hidden border-2 border-[var(--border)]">
                        <img :src="preview ?? '{{ $siswa->foto_url }}'"
                             alt="{{ $siswa->nama_lengkap }}"
                             class="h-full w-full object-cover">
                    </div>
                    <div class="w-full text-center">
                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-md border
                                      border-[var(--border)] bg-[var(--card)] px-4 py-2 text-sm font-medium
                                      text-[var(--foreground)] hover:bg-[var(--muted)] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                            </svg>
                            Ganti Foto
                            <input type="file" name="foto" accept="image/*" class="hidden"
                                   @change="preview = $event.target.files[0]
                                       ? URL.createObjectURL($event.target.files[0]) : null">
                        </label>
                        <p class="mt-2 text-xs text-[var(--muted-foreground)]">Kosongkan jika tidak ingin mengganti.</p>
                        @error('foto')<p class="mt-1 text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
                    </div>
                </div>
            </x-ui.section-card>

            {{-- Data Siswa --}}
            <div class="lg:col-span-2 space-y-6">

                <x-ui.section-card title="Identitas Siswa">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-ui.input name="nama_lengkap" label="Nama Lengkap"
                                placeholder="Nama lengkap siswa" :required="true"
                                :value="old('nama_lengkap', $siswa->nama_lengkap)" />
                        </div>
                        <x-ui.input name="nisn" label="NISN"
                            placeholder="Nomor Induk Siswa Nasional" :required="true"
                            :value="old('nisn', $siswa->nisn)" />
                        <x-ui.input name="nis" label="NIS"
                            placeholder="Nomor Induk Siswa Sekolah" :required="true"
                            :value="old('nis', $siswa->nis)" />

                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-[var(--foreground)]">
                                Jenis Kelamin <span class="text-[var(--destructive)] ml-0.5">*</span>
                            </label>
                            <select name="jenis_kelamin" required
                                    class="w-full h-9 rounded-md border px-3 text-sm outline-none transition
                                           bg-[var(--card)] text-[var(--foreground)] border-[var(--input)]
                                           focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                                <option value="">Pilih...</option>
                                <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-[var(--foreground)]">
                                Kelas <span class="text-[var(--destructive)] ml-0.5">*</span>
                            </label>
                            <select name="kelas_id" required
                                    class="w-full h-9 rounded-md border px-3 text-sm outline-none transition
                                           bg-[var(--card)] text-[var(--foreground)] border-[var(--input)]
                                           focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                                <option value="">Pilih kelas...</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}"
                                            {{ old('kelas_id', $siswa->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </x-ui.section-card>

                <x-ui.section-card title="Data Kelahiran & Kontak">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <x-ui.input name="tempat_lahir" label="Tempat Lahir" placeholder="Contoh: Bogor"
                            :value="old('tempat_lahir', $siswa->tempat_lahir)" />
                        <x-ui.input name="tanggal_lahir" label="Tanggal Lahir" type="date"
                            :value="old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d'))" />
                        <div class="sm:col-span-2">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-[var(--foreground)]">Alamat</label>
                                <textarea name="alamat" rows="2"
                                          class="w-full rounded-md border px-3 py-2 text-sm outline-none transition resize-y
                                                 bg-[var(--card)] text-[var(--foreground)] placeholder:text-[var(--muted-foreground)]
                                                 border-[var(--input)] focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20"
                                          placeholder="Alamat lengkap siswa">{{ old('alamat', $siswa->alamat) }}</textarea>
                            </div>
                        </div>
                        <x-ui.input name="no_hp" label="No. HP / WA Orang Tua"
                            placeholder="Contoh: 08123456789"
                            :value="old('no_hp', $siswa->no_hp)" />
                        <x-ui.input name="nama_orang_tua" label="Nama Orang Tua / Wali"
                            placeholder="Nama ayah / ibu / wali"
                            :value="old('nama_orang_tua', $siswa->nama_orang_tua)" />
                    </div>
                </x-ui.section-card>

                <div class="flex items-center gap-3">
                    <x-ui.button type="submit">Simpan Perubahan</x-ui.button>
                    <x-ui.button variant="secondary" href="{{ route('admin.siswa.show', $siswa) }}">Batal</x-ui.button>
                </div>
            </div>
        </div>
    </form>

@endsection
