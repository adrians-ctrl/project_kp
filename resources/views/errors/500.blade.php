<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Server</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[var(--background)] flex items-center justify-center">
    <div class="text-center px-6">
        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-orange-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-orange-500" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874
                         1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
            </svg>
        </div>
        <p class="text-6xl font-bold text-[var(--primary)] mb-4">500</p>
        <h1 class="text-xl font-semibold text-[var(--foreground)] mb-2">Terjadi Kesalahan</h1>
        <p class="text-sm text-[var(--muted-foreground)] mb-6 max-w-md mx-auto">
            Maaf, terjadi kesalahan pada server. Tim kami akan segera memperbaikinya.
            Silakan coba lagi beberapa saat.
        </p>
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[var(--primary)]
                                         text-white text-sm font-medium rounded-lg hover:opacity-90 transition-opacity">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
