<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SDN Babakan 02</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">

    <div class="w-full max-w-md">

        {{-- Logo & Judul --}}
        <div class="text-center mb-8">
            {{-- Disini kita paksa background-color pake style manual --}}
            <div class="inline-flex items-center justify-center w-16 h-16 text-white rounded-2xl mb-4" style="background-color: #1d4ed8;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                </svg>
            </div>
            <h1 class="text-2xl font-semibold text-gray-900">SDN Babakan 02</h1>
            <p class="text-sm text-gray-500 mt-1">Sistem Informasi Sekolah</p>
        </div>

        {{-- Card Form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            <h2 class="text-lg font-semibold text-gray-800 mb-6">Masuk ke Akun Anda</h2>

            {{-- Alert Error --}}
            @if ($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                               border-gray-300 bg-white text-gray-900 placeholder-gray-400
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100
                               @error('email') border-red-400 bg-red-50 @enderror"
                        placeholder="nama@sekolah.id"
                    >
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Password
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                        class="w-full px-4 py-2.5 text-sm border rounded-lg outline-none transition
                               border-gray-300 bg-white text-gray-900 placeholder-gray-400
                               focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                        placeholder="Masukkan password"
                    >
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center mb-6">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    >
                    <label for="remember" class="ml-2 text-sm text-gray-600">
                        Ingat saya
                    </label>
                </div>

                {{-- Submit --}}
                {{-- Disini tombol juga kita paksa warna birunya lewat style manual --}}
                <button
                    type="submit"
                    class="w-full py-2.5 px-4 text-white text-sm font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    style="background-color: #1d4ed8;"
                >
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            SDN Babakan 02 &copy; {{ date('Y') }}
        </p>

    </div>

</body>
</html>