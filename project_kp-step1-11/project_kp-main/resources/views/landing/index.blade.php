<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[var(--background)] text-[var(--foreground)]">
    <header class="bg-[var(--primary)] text-white py-4 px-6 flex items-center justify-between">
        <div>
            <h1 class="font-display text-lg font-bold">{{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}</h1>
            <p class="text-xs opacity-75">{{ $profil?->alamat_lengkap ?? '' }}</p>
        </div>
        <a href="{{ route('login') }}" class="text-sm bg-white text-[var(--primary)] px-4 py-2 rounded-md font-medium hover:opacity-90 transition-opacity">
            Login
        </a>
    </header>
    <main class="max-w-4xl mx-auto px-6 py-12 text-center">
        <h2 class="font-display text-3xl font-bold mb-4">
            Selamat Datang di {{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}
        </h2>
        @if ($visiMisi)
            <p class="text-[var(--muted-foreground)] text-lg max-w-2xl mx-auto leading-relaxed">
                {{ $visiMisi->visi }}
            </p>
        @endif
        <div class="mt-8 flex items-center justify-center gap-4 flex-wrap">
            <a href="{{ route('landing.berita') }}" class="px-6 py-3 bg-[var(--primary)] text-white rounded-lg text-sm font-medium hover:opacity-90 transition-opacity">
                Berita & Pengumuman
            </a>
            <a href="{{ route('landing.kontak') }}" class="px-6 py-3 border border-[var(--border)] rounded-lg text-sm font-medium hover:bg-[var(--muted)] transition-colors">
                Hubungi Kami
            </a>
        </div>
    </main>
    <footer class="border-t border-[var(--border)] py-6 text-center text-sm text-[var(--muted-foreground)]">
        &copy; {{ date('Y') }} {{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}
    </footer>
</body>
</html>