<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $profil?->nama_sekolah ?? 'SDN Babakan 02' }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-[var(--background)] text-[var(--foreground)] min-h-screen flex flex-col items-center justify-center p-6">
    <p class="text-[var(--muted-foreground)] mb-4">Halaman ini akan dilengkapi pada Step 17.</p>
    <a href="{{ route('landing') }}" class="text-sm text-[var(--primary)] hover:underline">Kembali ke Beranda</a>
</body>
</html>