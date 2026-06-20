@extends('layouts.guru')

@section('title', 'Input Absensi')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Guru</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Absensi</span>
@endsection

@section('content')

@if (! $guru)
    <x-ui.page-header title="Input Absensi" />
    <x-ui.section-card>
        <x-ui.empty-state
            title="Akun Anda belum terhubung ke data guru"
            message="Hubungi administrator untuk menautkan akun Anda ke data guru."
        />
    </x-ui.section-card>
@elseif ($kelasList->isEmpty())
    <x-ui.page-header title="Input Absensi" />
    <x-ui.section-card>
        <x-ui.empty-state
            title="Anda belum ditetapkan sebagai wali kelas"
            message="Hubungi administrator untuk informasi lebih lanjut."
        />
    </x-ui.section-card>
@else

<x-ui.page-header
    title="Input Absensi Harian"
    desc="Catat kehadiran siswa untuk kelas yang Anda ampu."
>
    <x-slot:actions>
        <x-ui.button variant="secondary" href="{{ route('guru.absensi.rekap-harian') }}">
            Rekap Harian
        </x-ui.button>
        <x-ui.button variant="secondary" href="{{ route('guru.absensi.rekap-bulanan') }}">
            Rekap Bulanan
        </x-ui.button>
    </x-slot:actions>
</x-ui.page-header>

{{-- Filter kelas & tanggal --}}
<x-ui.section-card>
    <form method="GET" action="{{ route('guru.absensi.index') }}">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <div class="sm:w-52">
                <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Kelas</label>
                <select name="kelas_id"
                        class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                               px-3 text-sm outline-none transition text-[var(--foreground)]
                               focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:w-48">
                <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                       class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                              px-3 text-sm outline-none transition text-[var(--foreground)]
                              focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
            </div>
            <x-ui.button type="submit">Tampilkan</x-ui.button>
        </div>
    </form>
</x-ui.section-card>

{{-- Form input absensi --}}
@if ($kelasId && $siswaList->isNotEmpty())

    @if ($sudahDiisi)
        <x-ui.alert type="warning"
            message="Absensi kelas ini untuk tanggal {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }} sudah diisi. Anda masih bisa mengubah status masing-masing siswa." />
    @endif

    <div x-data="absensiApp()">
    <x-ui.section-card
        :title="'Daftar Hadir — ' . $kelasList->firstWhere('id', $kelasId)?->nama_kelas . ', ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y')"
        :noPadding="true"
    >
        <x-slot:actions>
            <button type="button" @click="tandaiSemua('hadir')"
                    class="inline-flex items-center gap-1.5 rounded-md bg-green-50 px-3 py-1.5
                           text-xs font-medium text-green-700 hover:bg-green-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                </svg>
                Semua Hadir
            </button>
        </x-slot:actions>

        <form method="POST" action="{{ route('guru.absensi.store') }}" id="form-absensi">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
            <input type="hidden" name="tanggal"  value="{{ $tanggal }}">

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]" style="width:40px">No</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nama Siswa</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]" style="width:80px">NIS</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]" colspan="4">Status Kehadiran</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Keterangan</th>
                        </tr>
                        <tr class="border-b border-[var(--border)] bg-[var(--muted)]/30">
                            <th colspan="3"></th>
                            @foreach (['hadir'=>'Hadir','izin'=>'Izin','sakit'=>'Sakit','alpha'=>'Alpha'] as $val => $lbl)
                                <th class="px-3 py-2 text-center text-xs font-medium
                                           {{ $val === 'hadir' ? 'text-green-700' : ($val === 'izin' ? 'text-yellow-700' : ($val === 'sakit' ? 'text-blue-700' : 'text-red-700')) }}">
                                    {{ $lbl }}
                                </th>
                            @endforeach
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]" id="tbody-absensi">
                        @foreach ($siswaList as $i => $siswa)
                            @php
                                $existing      = $absensiHariIni->get($siswa->id);
                                $currentStatus = $existing?->status ?? 'hadir';
                            @endphp
                            <tr class="hover:bg-[var(--muted)]/30 transition-colors"
                                x-data="{ status: '{{ $currentStatus }}' }">
                                <input type="hidden" name="absensi[{{ $i }}][siswa_id]" value="{{ $siswa->id }}">

                                <td class="px-5 py-3 tabular-nums text-[var(--muted-foreground)]">{{ $i + 1 }}</td>
                                <td class="px-5 py-3 font-medium text-[var(--foreground)]">{{ $siswa->nama_lengkap }}</td>
                                <td class="px-5 py-3 font-mono text-xs text-[var(--muted-foreground)]">{{ $siswa->nis }}</td>

                                @foreach (['hadir','izin','sakit','alpha'] as $val)
                                    @php
                                        $colors = [
                                            'hadir' => 'text-green-600 border-green-400 bg-green-400',
                                            'izin'  => 'text-yellow-600 border-yellow-400 bg-yellow-400',
                                            'sakit' => 'text-blue-600 border-blue-400 bg-blue-400',
                                            'alpha' => 'text-red-600 border-red-400 bg-red-400',
                                        ];
                                    @endphp
                                    <td class="px-3 py-3 text-center">
                                        <label class="inline-flex items-center justify-center cursor-pointer">
                                            <input type="radio"
                                                   name="absensi[{{ $i }}][status]"
                                                   value="{{ $val }}"
                                                   x-model="status"
                                                   {{ $currentStatus === $val ? 'checked' : '' }}
                                                   class="sr-only">
                                            <span @click="status = '{{ $val }}'"
                                                  class="flex h-7 w-7 items-center justify-center rounded-full border-2 transition-all cursor-pointer
                                                         {{ $colors[$val] }}"
                                                  :class="status === '{{ $val }}'
                                                      ? 'border-current opacity-100 shadow-sm'
                                                      : 'border-[var(--border)] bg-transparent opacity-30 hover:opacity-60'">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd"/>
                                                </svg>
                                            </span>
                                        </label>
                                    </td>
                                @endforeach

                                <td class="px-5 py-3">
                                    <input type="text"
                                           name="absensi[{{ $i }}][keterangan]"
                                           value="{{ $existing?->keterangan }}"
                                           placeholder="Opsional..."
                                           class="w-full h-8 rounded-md border border-[var(--input)] bg-[var(--card)]
                                                  px-3 text-xs outline-none transition text-[var(--foreground)]
                                                  placeholder:text-[var(--muted-foreground)]
                                                  focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-[var(--border)] px-5 py-4">
                <p class="text-sm text-[var(--muted-foreground)]">{{ $siswaList->count() }} siswa</p>
                <x-ui.button type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                    </svg>
                    Simpan Absensi
                </x-ui.button>
            </div>
        </form>
    </x-ui.section-card>
    </div>

@else
    <x-ui.section-card>
        <x-ui.empty-state
            title="Kelas ini belum memiliki siswa"
            message="Hubungi administrator untuk menambahkan siswa ke kelas ini."
        />
    </x-ui.section-card>
@endif

@endif

@endsection

@push('scripts')
<script>
function absensiApp() {
    return {
        tandaiSemua(status) {
            const rows = document.querySelectorAll('#tbody-absensi tr');
            rows.forEach(row => {
                const component = Alpine.$data(row);
                if (component && 'status' in component) {
                    component.status = status;
                }
                const radio = row.querySelector(`input[type="radio"][value="${status}"]`);
                if (radio) radio.checked = true;
            });
        },
    }
}
</script>
@endpush
