@extends('layouts.admin')

@section('title', 'Edit Guru & Staf')

@section('breadcrumb')
    <a href="{{ route('admin.guru.index') }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
        Guru & Staf
    </a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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

    <form method="POST" action="{{ route('admin.guru.update', $guru) }}"
          enctype="multipart/form-data" x-data="{ preview: null }">
        @csrf
        @method('PUT')

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Kolom kiri: Foto --}}
            <x-ui.section-card title="Foto Profil">
                <div class="flex flex-col items-center gap-4">

                    <div class="relative">
                        <div class="h-32 w-32 rounded-full overflow-hidden border-2 border-[var(--border)]">
                            <img :src="preview ?? '{{ $guru->foto_url }}'"
                                 alt="{{ $guru->nama_lengkap }}"
                                 class="h-full w-full object-cover">
                        </div>
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
                        <p class="mt-2 text-xs text-[var(--muted-foreground)]">
                            Kosongkan jika tidak ingin mengganti foto.
                        </p>
                        @error('foto')
                            <p class="mt-1 text-xs text-[var(--destructive)]">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info akun terhubung --}}
                    <div class="w-full rounded-lg border border-[var(--border)] p-3">
                        <p class="text-xs font-medium text-[var(--muted-foreground)] mb-1.5">
                            Status Akun
                        </p>
                        @if ($guru->user)
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-green-500 shrink-0"></span>
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-[var(--foreground)] truncate">
                                        {{ $guru->user->name }}
                                    </p>
                                    <p class="text-xs text-[var(--muted-foreground)] truncate">
                                        {{ $guru->user->email }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full bg-[var(--muted-foreground)] shrink-0"></span>
                                <p class="text-xs text-[var(--muted-foreground)]">Belum ada akun</p>
                            </div>
                        @endif
                    </div>
                </div>
            </x-ui.section-card>

            {{-- Kolom kanan: Data diri --}}
            <div class="lg:col-span-2 space-y-6">

                <x-ui.section-card title="Data Diri">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <x-ui.input
                                name="nama_lengkap"
                                label="Nama Lengkap"
                                placeholder="Contoh: Budi Santoso, S.Pd."
                                :required="true"
                                :value="old('nama_lengkap', $guru->nama_lengkap)"
                            />
                        </div>

                        <x-ui.input
                            name="nip"
                            label="NIP"
                            placeholder="Nomor Induk Pegawai"
                            hint="Kosongkan jika honorer atau non-PNS."
                            :value="old('nip', $guru->nip)"
                        />

                        <x-ui.input
                            name="no_hp"
                            label="Nomor HP"
                            placeholder="Contoh: 08123456789"
                            :value="old('no_hp', $guru->no_hp)"
                        />

                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-[var(--foreground)]">
                                Jabatan <span class="text-[var(--destructive)] ml-0.5">*</span>
                            </label>
                            <select name="jabatan" required
                                    class="w-full h-9 rounded-md border px-3 text-sm outline-none transition
                                           bg-[var(--card)] text-[var(--foreground)] border-[var(--input)]
                                           focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20
                                           {{ $errors->has('jabatan') ? 'border-red-400 bg-red-50' : '' }}">
                                <option value="">Pilih jabatan...</option>
                                @foreach ([
                                    'Kepala Sekolah',
                                    'Guru Kelas',
                                    'Guru PAI',
                                    'Guru PJOK',
                                    'Guru Bahasa Inggris',
                                    'Guru Seni Budaya',
                                    'Tata Usaha',
                                    'Operator Sekolah',
                                    'Penjaga Sekolah',
                                ] as $j)
                                    <option value="{{ $j }}"
                                            {{ old('jabatan', $guru->jabatan) === $j ? 'selected' : '' }}>
                                        {{ $j }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jabatan')
                                <p class="text-xs text-[var(--destructive)]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-sm font-medium text-[var(--foreground)]">
                                Pendidikan Terakhir
                            </label>
                            <select name="pendidikan"
                                    class="w-full h-9 rounded-md border px-3 text-sm outline-none transition
                                           bg-[var(--card)] text-[var(--foreground)] border-[var(--input)]
                                           focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                                <option value="">Pilih pendidikan...</option>
                                @foreach (['SMA/SMK', 'D3', 'S1', 'S2', 'S3'] as $p)
                                    <option value="{{ $p }}"
                                            {{ old('pendidikan', $guru->pendidikan) === $p ? 'selected' : '' }}>
                                        {{ $p }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <x-ui.input
                                name="mapel"
                                label="Mata Pelajaran Diampu"
                                placeholder="Contoh: Matematika"
                                hint="Kosongkan jika bukan tenaga pengajar."
                                :value="old('mapel', $guru->mapel)"
                            />
                        </div>
                    </div>
                </x-ui.section-card>

                <x-ui.section-card
                    title="Hubungkan ke Akun Login"
                    description="Tautkan atau ubah akun yang terhubung ke data guru ini."
                >
                    <div class="space-y-1.5">
                        <label class="block text-sm font-medium text-[var(--foreground)]">
                            Akun Pengguna
                        </label>
                        <select name="user_id"
                                class="w-full h-9 rounded-md border px-3 text-sm outline-none transition
                                       bg-[var(--card)] text-[var(--foreground)] border-[var(--input)]
                                       focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20
                                       {{ $errors->has('user_id') ? 'border-red-400 bg-red-50' : '' }}">
                            <option value="">Tidak dihubungkan</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                        {{ old('user_id', $guru->user_id) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-xs text-[var(--destructive)]">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-[var(--muted-foreground)]">
                            Hanya akun role <span class="font-medium">guru</span> yang belum terhubung
                            ke data guru lain yang ditampilkan.
                        </p>
                    </div>
                </x-ui.section-card>

                <div class="flex items-center gap-3">
                    <x-ui.button type="submit">Simpan Perubahan</x-ui.button>
                    <x-ui.button variant="secondary" href="{{ route('admin.guru.index') }}">
                        Batal
                    </x-ui.button>
                </div>
            </div>
        </div>
    </form>

@endsection
