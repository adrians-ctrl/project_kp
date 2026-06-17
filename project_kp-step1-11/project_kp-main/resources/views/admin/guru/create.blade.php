@extends('layouts.admin')

@section('title', 'Tambah Guru & Staf')

@section('breadcrumb')
    <a href="{{ route('admin.guru.index') }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
        Guru & Staf
    </a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Tambah</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Tambah Guru & Staf"
        desc="Tambahkan data tenaga pengajar atau staf administrasi baru."
        :back="route('admin.guru.index')"
    />

    <form method="POST" action="{{ route('admin.guru.store') }}"
          enctype="multipart/form-data" x-data="{ preview: null }">
        @csrf

        <div class="grid gap-6 lg:grid-cols-3">

            {{-- Kolom kiri: Foto --}}
            <x-ui.section-card title="Foto Profil">
                <div class="flex flex-col items-center gap-4">

                    {{-- Preview avatar --}}
                    <div class="relative">
                        <div class="h-32 w-32 rounded-full overflow-hidden border-2 border-dashed
                                    border-[var(--border)] bg-[var(--muted)] flex items-center justify-center">
                            <img x-show="preview" :src="preview"
                                 class="h-full w-full object-cover rounded-full">
                            <div x-show="!preview"
                                 class="flex flex-col items-center gap-1 text-[var(--muted-foreground)]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Input file --}}
                    <div class="w-full text-center">
                        <label class="inline-flex cursor-pointer items-center gap-2 rounded-md border
                                      border-[var(--border)] bg-[var(--card)] px-4 py-2 text-sm font-medium
                                      text-[var(--foreground)] hover:bg-[var(--muted)] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                            </svg>
                            Pilih Foto
                            <input type="file" name="foto" accept="image/*" class="hidden"
                                   @change="preview = $event.target.files[0]
                                       ? URL.createObjectURL($event.target.files[0]) : null">
                        </label>
                        <p class="mt-2 text-xs text-[var(--muted-foreground)]">
                            JPG, PNG, WebP. Maks. 2MB.
                        </p>
                        @error('foto')
                            <p class="mt-1 text-xs text-[var(--destructive)]">{{ $message }}</p>
                        @enderror
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
                            />
                        </div>

                        <x-ui.input
                            name="nip"
                            label="NIP"
                            placeholder="Nomor Induk Pegawai"
                            hint="Kosongkan jika honorer atau non-PNS."
                        />

                        <x-ui.input
                            name="no_hp"
                            label="Nomor HP"
                            placeholder="Contoh: 08123456789"
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
                                    <option value="{{ $j }}" {{ old('jabatan') === $j ? 'selected' : '' }}>
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
                                    <option value="{{ $p }}" {{ old('pendidikan') === $p ? 'selected' : '' }}>
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
                            />
                        </div>
                    </div>
                </x-ui.section-card>

                <x-ui.section-card
                    title="Hubungkan ke Akun Login"
                    description="Opsional. Tautkan data ini ke akun user agar guru dapat login ke sistem."
                >
                    @if ($users->isEmpty())
                        <div class="flex items-start gap-3 rounded-lg bg-[var(--muted)] p-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mt-0.5 shrink-0 text-[var(--muted-foreground)]"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9-3.75h.008v.008H12V8.25z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-[var(--foreground)]">
                                    Tidak ada akun yang tersedia
                                </p>
                                <p class="text-xs text-[var(--muted-foreground)] mt-0.5">
                                    Semua akun dengan role guru sudah terhubung ke data guru lain.
                                    Buat akun baru melalui menu
                                    <a href="{{ route('admin.users.index') }}"
                                       class="text-[var(--primary)] hover:underline">Manajemen User</a>.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="space-y-3">
                            <div class="space-y-1.5">
                                <label class="block text-sm font-medium text-[var(--foreground)]">
                                    Pilih Akun
                                </label>
                                <select name="user_id"
                                        class="w-full h-9 rounded-md border px-3 text-sm outline-none transition
                                               bg-[var(--card)] text-[var(--foreground)] border-[var(--input)]
                                               focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20
                                               {{ $errors->has('user_id') ? 'border-red-400 bg-red-50' : '' }}">
                                    <option value="">Tidak dihubungkan</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                                {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <p class="text-xs text-[var(--destructive)]">{{ $message }}</p>
                                @enderror
                            </div>

                            <p class="text-xs text-[var(--muted-foreground)]">
                                Hanya akun dengan role <span class="font-medium">guru</span> yang belum
                                terhubung yang ditampilkan di sini.
                            </p>
                        </div>
                    @endif
                </x-ui.section-card>

                <div class="flex items-center gap-3">
                    <x-ui.button type="submit">Simpan Data</x-ui.button>
                    <x-ui.button variant="secondary" href="{{ route('admin.guru.index') }}">
                        Batal
                    </x-ui.button>
                </div>
            </div>
        </div>
    </form>

@endsection
