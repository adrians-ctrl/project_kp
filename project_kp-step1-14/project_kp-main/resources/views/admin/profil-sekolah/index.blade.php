@extends('layouts.admin')

@section('title', 'Profil Sekolah')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Profil Sekolah</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Profil Sekolah"
        desc="Kelola informasi identitas sekolah, visi misi, dan sambutan kepala sekolah."
    />

    <div class="grid gap-6 lg:grid-cols-3">

        {{-- Kolom kiri: Logo --}}
        <div class="space-y-6">
            <x-ui.section-card title="Logo Sekolah">
                <div class="flex flex-col items-center gap-4" x-data="{ preview: null }">
                    <div class="h-32 w-32 rounded-xl overflow-hidden border-2 border-[var(--border)]
                                bg-[var(--muted)] flex items-center justify-center">
                        <img :src="preview ?? '{{ $profil?->logo_url ?? '' }}'"
                             alt="Logo Sekolah"
                             class="h-full w-full object-contain p-2"
                             onerror="this.style.display='none'">
                    </div>
                    <p class="text-center text-xs text-[var(--muted-foreground)]">
                        Logo ditampilkan di halaman publik dan dokumen cetak.
                    </p>
                </div>
            </x-ui.section-card>

            {{-- Info cepat --}}
            @if ($profil)
                <x-ui.section-card title="Informasi Cepat">
                    <dl class="space-y-3 text-sm">
                        @foreach ([
                            ['label' => 'NPSN',        'value' => $profil->npsn       ?? '—'],
                            ['label' => 'Akreditasi',  'value' => $profil->akreditasi  ?? '—'],
                            ['label' => 'Kota',        'value' => $profil->kota        ?? '—'],
                            ['label' => 'Telepon',     'value' => $profil->telepon     ?? '—'],
                            ['label' => 'Email',       'value' => $profil->email       ?? '—'],
                        ] as $item)
                            <div class="flex items-start justify-between gap-3">
                                <dt class="text-[var(--muted-foreground)] shrink-0">{{ $item['label'] }}</dt>
                                <dd class="text-right text-[var(--foreground)] font-medium">{{ $item['value'] }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </x-ui.section-card>
            @endif
        </div>

        {{-- Kolom kanan: Form --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Form Profil Sekolah --}}
            @if ($profil)
                <x-ui.section-card title="Data Identitas Sekolah">
                    <form method="POST"
                          action="{{ route('admin.profil-sekolah.update', $profil) }}"
                          enctype="multipart/form-data"
                          class="space-y-5"
                          x-data="{ preview: null }">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <x-ui.input name="nama_sekolah" label="Nama Sekolah"
                                    :required="true" :value="old('nama_sekolah', $profil->nama_sekolah)" />
                            </div>
                            <x-ui.input name="npsn" label="NPSN"
                                :value="old('npsn', $profil->npsn)" />
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-[var(--foreground)]">Akreditasi</label>
                                <select name="akreditasi"
                                        class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                               px-3 text-sm outline-none transition text-[var(--foreground)]
                                               focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                                    <option value="">Pilih...</option>
                                    @foreach (['A', 'B', 'C', 'TT'] as $ak)
                                        <option value="{{ $ak }}"
                                                {{ old('akreditasi', $profil->akreditasi) === $ak ? 'selected' : '' }}>
                                            {{ $ak === 'TT' ? 'Tidak Terakreditasi' : 'Akreditasi ' . $ak }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <x-ui.textarea name="alamat" label="Alamat" :required="true" :rows="2"
                                    placeholder="Alamat lengkap sekolah">{{ old('alamat', $profil->alamat) }}</x-ui.textarea>
                            </div>
                            <x-ui.input name="kelurahan" label="Kelurahan"
                                :value="old('kelurahan', $profil->kelurahan)" />
                            <x-ui.input name="kecamatan" label="Kecamatan"
                                :value="old('kecamatan', $profil->kecamatan)" />
                            <x-ui.input name="kota" label="Kota / Kabupaten"
                                :value="old('kota', $profil->kota)" />
                            <x-ui.input name="provinsi" label="Provinsi"
                                :value="old('provinsi', $profil->provinsi)" />
                            <x-ui.input name="kode_pos" label="Kode Pos"
                                :value="old('kode_pos', $profil->kode_pos)" />
                            <x-ui.input name="telepon" label="Telepon"
                                :value="old('telepon', $profil->telepon)" />
                            <x-ui.input name="email" label="Email Sekolah" type="email"
                                :value="old('email', $profil->email)" />
                            <x-ui.input name="website" label="Website" type="url"
                                placeholder="https://..." :value="old('website', $profil->website)" />
                        </div>

                        {{-- Upload Logo --}}
                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-[var(--foreground)]">Logo Sekolah</label>
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 rounded-lg overflow-hidden border border-[var(--border)]
                                            bg-[var(--muted)] flex items-center justify-center shrink-0">
                                    <img :src="preview ?? '{{ $profil->logo_url }}'"
                                         class="h-full w-full object-contain p-1"
                                         onerror="this.style.display='none'">
                                </div>
                                <div>
                                    <label class="inline-flex cursor-pointer items-center gap-2 rounded-md border
                                                  border-[var(--border)] bg-[var(--card)] px-4 py-2 text-sm
                                                  font-medium text-[var(--foreground)] hover:bg-[var(--muted)]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                        </svg>
                                        Ganti Logo
                                        <input type="file" name="logo" accept="image/*" class="hidden"
                                               @change="preview = $event.target.files[0]
                                                   ? URL.createObjectURL($event.target.files[0]) : null">
                                    </label>
                                    <p class="mt-1 text-xs text-[var(--muted-foreground)]">PNG/JPG/WebP, maks. 2MB.</p>
                                    @error('logo')<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <x-ui.button type="submit">Simpan Perubahan</x-ui.button>
                        </div>
                    </form>
                </x-ui.section-card>
            @endif

            {{-- Form Visi Misi --}}
            <x-ui.section-card title="Visi & Misi">
                <form method="POST" action="{{ route('admin.visi-misi.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-[var(--foreground)]">
                            Visi <span class="text-[var(--destructive)] ml-0.5">*</span>
                        </label>
                        <textarea name="visi" rows="3" required
                                  class="w-full rounded-md border border-[var(--input)] bg-[var(--card)]
                                         px-3 py-2 text-sm outline-none transition resize-y
                                         text-[var(--foreground)] placeholder:text-[var(--muted-foreground)]
                                         focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20
                                         {{ $errors->has('visi') ? 'border-red-400 bg-red-50' : '' }}"
                                  placeholder="Visi sekolah...">{{ old('visi', $visiMisi?->visi) }}</textarea>
                        @error('visi')<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-[var(--foreground)]">
                            Misi <span class="text-[var(--destructive)] ml-0.5">*</span>
                        </label>
                        <textarea name="misi" rows="6" required
                                  class="w-full rounded-md border border-[var(--input)] bg-[var(--card)]
                                         px-3 py-2 text-sm outline-none transition resize-y
                                         text-[var(--foreground)] placeholder:text-[var(--muted-foreground)]
                                         focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20
                                         {{ $errors->has('misi') ? 'border-red-400 bg-red-50' : '' }}"
                                  placeholder="1. Misi pertama&#10;2. Misi kedua&#10;3. Misi ketiga">{{ old('misi', $visiMisi?->misi) }}</textarea>
                        @error('misi')<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
                        <p class="text-xs text-[var(--muted-foreground)]">
                            Gunakan baris baru untuk memisahkan setiap poin misi.
                        </p>
                    </div>

                    <x-ui.button type="submit">Simpan Visi & Misi</x-ui.button>
                </form>
            </x-ui.section-card>

            {{-- Form Sambutan Kepala Sekolah --}}
            @if ($profil)
                <x-ui.section-card title="Sambutan Kepala Sekolah"
                    description="Ditampilkan pada halaman publik sekolah.">
                    <form method="POST"
                          action="{{ route('admin.profil-sekolah.update', $profil) }}"
                          enctype="multipart/form-data"
                          class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Kirim ulang field wajib agar tidak terkena validasi --}}
                        <input type="hidden" name="nama_sekolah" value="{{ $profil->nama_sekolah }}">
                        <input type="hidden" name="alamat"       value="{{ $profil->alamat }}">

                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-[var(--foreground)]">
                                Teks Sambutan
                            </label>
                            <textarea name="sambutan" rows="8"
                                      class="w-full rounded-md border border-[var(--input)] bg-[var(--card)]
                                             px-3 py-2 text-sm outline-none transition resize-y
                                             text-[var(--foreground)] placeholder:text-[var(--muted-foreground)]
                                             focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20"
                                      placeholder="Assalamu'alaikum Wr. Wb.&#10;&#10;Puji syukur kami panjatkan...">{{ old('sambutan', $profil->sambutan) }}</textarea>
                            @error('sambutan')
                                <p class="text-xs text-[var(--destructive)]">{{ $message }}</p>
                            @enderror
                        </div>

                        <x-ui.button type="submit">Simpan Sambutan</x-ui.button>
                    </form>
                </x-ui.section-card>
            @endif

        </div>
    </div>

@endsection
