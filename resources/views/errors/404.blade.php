<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[var(--background)] flex items-center justify-center">
    <div class="text-center">
        <p class="text-6xl font-bold text-[var(--primary)] mb-4">404</p>
        <h1 class="text-xl font-semibold text-[var(--foreground)] mb-2">Halaman Tidak Ditemukan</h1>
        <p class="text-sm text-[var(--muted-foreground)] mb-6">Halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[var(--primary)] text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>