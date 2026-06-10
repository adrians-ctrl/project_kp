@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <span class="text-[var(--muted-foreground)]/50">/</span>
    <span class="font-medium text-[var(--foreground)]">Dashboard</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Dashboard Administrator"
        desc="Ringkasan statistik akademik dan aktivitas sistem hari ini."
    />

    {{-- Stat Cards --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <x-ui.stat-card
            label="Total Siswa"
            value="0"
            delta="Belum ada data"
            trend="neutral"
            icon='<path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>'
        />
        <x-ui.stat-card
            label="Total Guru & Staf"
            value="0"
            delta="Belum ada data"
            trend="neutral"
            icon='<path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 013.74-3.342"/>'
        />
        <x-ui.stat-card
            label="Total Kelas"
            value="0"
            delta="Belum ada data"
            trend="neutral"
            icon='<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>'
        />
        <x-ui.stat-card
            label="Mata Pelajaran"
            value="0"
            delta="Belum ada data"
            trend="neutral"
            icon='<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>'
        />
    </div>

    {{-- Content Area --}}
    <x-ui.section-card title="Selamat Datang" description="Layout dan komponen berhasil dimuat.">
        <p class="text-sm text-[var(--muted-foreground)]">
            Dashboard akan dilengkapi pada Step 5. Sidebar dapat ditutup dengan tombol panah
            di kiri atas, dan responsif di perangkat mobile.
        </p>
    </x-ui.section-card>

@endsection
