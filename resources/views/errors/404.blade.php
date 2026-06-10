<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="text-center">
        <p class="text-6xl font-bold text-primary-700 mb-4">404</p>
        <h1 class="text-xl font-semibold text-gray-800 mb-2">Halaman Tidak Ditemukan</h1>
        <p class="text-sm text-gray-500 mb-6">Halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-700 hover:bg-primary-800 text-white text-sm font-medium rounded-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>