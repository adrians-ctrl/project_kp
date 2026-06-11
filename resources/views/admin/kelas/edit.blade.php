@extends('layouts.admin')

@section('title', 'Edit Kelas')

@section('breadcrumb')
    <a href="{{ route('admin.kelas.index') }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
        Kelas
    </a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Edit</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Edit Kelas"
        desc="Perbarui informasi kelas {{ $kelas->nama_kelas }}."
        :back="route('admin.kelas.index')"
    />

    <x-ui.section-card title="Informasi Kelas" class="max-w-xl">
        <form method="POST" action="{{ route('admin.kelas.update', $kelas) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <x-ui.input
                name="nama_kelas"
                label="Nama Kelas"
                placeholder="Contoh: Kelas 1A"
                :required="true"
                :value="old('nama_kelas', $kelas->nama_kelas)"
            />

            <div class="grid grid-cols-2 gap-4">
                <x-ui.input
                    name="tingkat"
                    label="Tingkat"
                    placeholder="Contoh: 1"
                    :required="true"
                    :value="old('tingkat', $kelas->tingkat)"
                />
                <x-ui.input
                    name="tahun_ajaran"
                    label="Tahun Ajaran"
                    placeholder="Contoh: 2025/2026"
                    :required="true"
                    :value="old('tahun_ajaran', $kelas->tahun_ajaran)"
                />
            </div>

            <x-ui.select
                name="wali_kelas_id"
                label="Wali Kelas"
                :options="$guruList->pluck('nama_lengkap', 'id')"
                placeholder="Pilih wali kelas (opsional)"
                :selected="old('wali_kelas_id', $kelas->wali_kelas_id)"
            />

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit">
                    Simpan Perubahan
                </x-ui.button>
                <x-ui.button variant="secondary" href="{{ route('admin.kelas.index') }}">
                    Batal
                </x-ui.button>
            </div>
        </form>
    </x-ui.section-card>

@endsection
