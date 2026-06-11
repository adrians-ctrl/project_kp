@extends('layouts.admin')

@section('title', 'Edit Mata Pelajaran')

@section('breadcrumb')
    <a href="{{ route('admin.mata-pelajaran.index') }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
        Mata Pelajaran
    </a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Edit</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Edit Mata Pelajaran"
        desc="Perbarui informasi mata pelajaran {{ $mapel->nama_mapel }}."
        :back="route('admin.mata-pelajaran.index')"
    />

    <x-ui.section-card title="Informasi Mata Pelajaran" class="max-w-sm">
        <form method="POST"
              action="{{ route('admin.mata-pelajaran.update', $mapel) }}"
              class="space-y-5">
            @csrf
            @method('PUT')

            <x-ui.input
                name="kode_mapel"
                label="Kode Mata Pelajaran"
                placeholder="Contoh: MTK"
                :required="true"
                hint="Gunakan kode singkat unik, maksimal 20 karakter."
                :value="old('kode_mapel', $mapel->kode_mapel)"
            />

            <x-ui.input
                name="nama_mapel"
                label="Nama Mata Pelajaran"
                placeholder="Contoh: Matematika"
                :required="true"
                :value="old('nama_mapel', $mapel->nama_mapel)"
            />

            <div class="flex items-center gap-3 pt-2">
                <x-ui.button type="submit">
                    Simpan Perubahan
                </x-ui.button>
                <x-ui.button variant="secondary"
                             href="{{ route('admin.mata-pelajaran.index') }}">
                    Batal
                </x-ui.button>
            </div>
        </form>
    </x-ui.section-card>

@endsection
