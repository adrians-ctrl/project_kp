@extends('layouts.admin')

@section('title', 'Input Absensi')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Absensi</span>
@endsection

@section('content')

<div x-data="absensiApp()" x-init="init()">

    <x-ui.page-header
        title="Input Absensi Harian"
        desc="Catat kehadiran siswa per kelas setiap hari."
    >
        <x-slot:actions>
            <x-ui.button variant="secondary" href="{{ route('admin.absensi.rekap-harian') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/>
                </svg>
                Rekap Harian
            </x-ui.button>
            <x-ui.button variant="secondary" href="{{ route('admin.absensi.rekap-bulanan') }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                </svg>
                Rekap Bulanan
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Ringkasan hari ini --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        @foreach ([
            ['label'=>'Hadir', 'key'=>'hadir', 'color'=>'text-green-600', 'bg'=>'bg-green-50', 'ring'=>'ring-green-200'],
            ['label'=>'Izin',  'key'=>'izin',  'color'=>'text-yellow-600','bg'=>'bg-yellow-50','ring'=>'ring-yellow-200'],
            ['label'=>'Sakit', 'key'=>'sakit', 'color'=>'text-blue-600',  'bg'=>'bg-blue-50',  'ring'=>'ring-blue-200'],
            ['label'=>'Alpha', 'key'=>'alpha', 'color'=>'text-red-600',   'bg'=>'bg-red-50',   'ring'=>'ring-red-200'],
        ] as $item)
            <div class="rounded-xl border border-[var(--border)] {{ $item['bg'] }} p-4">
                <div class="font-display text-3xl font-bold {{ $item['color'] }} tabular-nums">
                    {{ $ringkasan[$item['key']] }}
                </div>
                <div class="mt-1 text-xs font-medium text-[var(--muted-foreground)] uppercase tracking-wide">
                    {{ $item['label'] }} hari ini
                </div>
                <div class="mt-1 text-xs text-[var(--muted-foreground)]">
                    dari {{ $ringkasan['total'] }} tercatat
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filter kelas & tanggal --}}
    <x-ui.section-card>
        <form method="GET" action="{{ route('admin.absensi.index') }}" id="form-filter">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="sm:w-52">
                    <label class="block text-xs font-medium text-[var(--muted-foreground)] mb-1.5">Kelas</label>
                    <select name="kelas_id" id="select-kelas"
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="">Pilih kelas...</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas->id }}"
                                    {{ $kelasId == $kelas->id ? 'selected' : '' }}>
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

        <x-ui.section-card
            :title="'Daftar Hadir — ' . $kelasList->firstWhere('id', $kelasId)?->nama_kelas . ', ' . \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y')"
            :noPadding="true"
        >
            <x-slot:actions>
                {{-- Tandai semua hadir --}}
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

            <form method="POST" action="{{ route('admin.absensi.store') }}" id="form-absensi">
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
                                    $existing = $absensiHariIni->get($siswa->id);
                                    $currentStatus = $existing?->status ?? 'hadir';
                                @endphp
                                <tr class="hover:bg-[var(--muted)]/30 transition-colors"
                                    x-data="{ status: '{{ $currentStatus }}' }">
                                    <input type="hidden" name="absensi[{{ $i }}][siswa_id]" value="{{ $siswa->id }}">

                                    <td class="px-5 py-3 tabular-nums text-[var(--muted-foreground)]">{{ $i + 1 }}</td>
                                    <td class="px-5 py-3 font-medium text-[var(--foreground)]">{{ $siswa->nama_lengkap }}</td>
                                    <td class="px-5 py-3 font-mono text-xs text-[var(--muted-foreground)]">{{ $siswa->nis }}</td>

                                    {{-- Radio status --}}
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

                                    {{-- Keterangan --}}
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

                {{-- Tombol simpan --}}
                <div class="flex items-center justify-between border-t border-[var(--border)] px-5 py-4">
                    <p class="text-sm text-[var(--muted-foreground)]">
                        {{ $siswaList->count() }} siswa
                    </p>
                    <div class="flex items-center gap-3">
                        <x-ui.button variant="secondary" type="button"
                                     onclick="window.location='{{ route('admin.absensi.index') }}'">
                            Batal
                        </x-ui.button>
                        <x-ui.button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                            </svg>
                            Simpan Absensi
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </x-ui.section-card>

    @elseif ($kelasId && $siswaList->isEmpty())
        <x-ui.section-card>
            <x-ui.empty-state
                title="Kelas ini belum memiliki siswa"
                message="Tambahkan siswa ke kelas ini terlebih dahulu."
            >
                <x-ui.button href="{{ route('admin.siswa.create') }}" size="sm">
                    Tambah Siswa
                </x-ui.button>
            </x-ui.empty-state>
        </x-ui.section-card>
    @else
        <x-ui.section-card>
            <x-ui.empty-state
                title="Pilih kelas untuk memulai"
                message="Pilih kelas dan tanggal di atas, lalu klik Tampilkan untuk mengisi absensi."
                icon='<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>'
            />
        </x-ui.section-card>
    @endif

</div>

@endsection

@push('scripts')
<script>
function absensiApp() {
    return {
        init() {},

        tandaiSemua(status) {
            // Update semua Alpine x-data status di baris tabel
            const rows = document.querySelectorAll('#tbody-absensi tr');
            rows.forEach(row => {
                const component = Alpine.$data(row);
                if (component && 'status' in component) {
                    component.status = status;
                }
                // Pastikan radio input juga ter-set
                const radio = row.querySelector(`input[type="radio"][value="${status}"]`);
                if (radio) radio.checked = true;
            });
        },
    }
}
</script>
@endpush
