<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SDN Babakan 02</title>
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --color-primary:        #1e40af;
            --color-primary-hover:  #1d3d9e;
            --color-primary-ring:   rgba(30, 64, 175, 0.15);
            --color-bg:             #f3f4f6;
            --color-surface:        #ffffff;
            --color-border:         #e5e7eb;
            --color-border-focus:   #1e40af;
            --color-border-error:   #f87171;
            --color-bg-error:       #fef2f2;
            --color-text:           #111827;
            --color-text-secondary: #6b7280;
            --color-text-label:     #374151;
            --color-text-error:     #b91c1c;
            --color-text-muted:     #9ca3af;
            --color-placeholder:    #9ca3af;
            --radius-sm:            6px;
            --radius-md:            10px;
            --radius-lg:            16px;
            --shadow-card:          0 1px 3px rgba(0,0,0,.07), 0 1px 2px rgba(0,0,0,.05);
        }

        body {
            min-height: 100vh;
            background-color: var(--color-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto,
                         'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: var(--color-text);
            padding: 24px 16px;
        }

        /* ---- Wrapper ---- */
        .login-wrapper {
            width: 100%;
            max-width: 400px;
        }

        /* ---- Header / Branding ---- */
        .login-brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-brand-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            background-color: var(--color-primary);
            border-radius: var(--radius-lg);
            margin-bottom: 14px;
        }

        .login-brand-icon svg {
            width: 28px;
            height: 28px;
            color: #ffffff;
        }

        .login-brand-name {
            font-size: 20px;
            font-weight: 600;
            color: var(--color-text);
            letter-spacing: -0.01em;
        }

        .login-brand-sub {
            font-size: 13px;
            color: var(--color-text-secondary);
            margin-top: 3px;
        }

        /* ---- Card ---- */
        .login-card {
            background-color: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            padding: 32px;
        }

        .login-card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-text);
            margin-bottom: 24px;
        }

        /* ---- Alert ---- */
        .alert-error {
            background-color: var(--color-bg-error);
            border: 1px solid var(--color-border-error);
            border-radius: var(--radius-md);
            padding: 12px 14px;
            margin-bottom: 20px;
        }

        .alert-error p {
            font-size: 13px;
            color: var(--color-text-error);
        }

        /* ---- Form ---- */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: var(--color-text-label);
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            padding: 9px 12px;
            font-size: 14px;
            font-family: inherit;
            color: var(--color-text);
            background-color: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            -webkit-appearance: none;
        }

        .form-input::placeholder {
            color: var(--color-placeholder);
        }

        .form-input:focus {
            border-color: var(--color-border-focus);
            box-shadow: 0 0 0 3px var(--color-primary-ring);
        }

        .form-input.is-error {
            border-color: var(--color-border-error);
            background-color: var(--color-bg-error);
        }

        /* ---- Remember Me ---- */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 22px;
        }

        .form-check input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border: 1px solid var(--color-border);
            border-radius: 4px;
            accent-color: var(--color-primary);
            cursor: pointer;
            flex-shrink: 0;
        }

        .form-check label {
            font-size: 13px;
            color: var(--color-text-secondary);
            cursor: pointer;
            user-select: none;
        }

        /* ---- Submit Button ---- */
        .btn-submit {
            display: block;
            width: 100%;
            padding: 10px 16px;
            font-size: 14px;
            font-weight: 500;
            font-family: inherit;
            color: #ffffff;
            background-color: var(--color-primary);
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: background-color 0.15s, transform 0.1s;
            text-align: center;
        }

        .btn-submit:hover {
            background-color: var(--color-primary-hover);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .btn-submit:focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }

        /* ---- Footer ---- */
        .login-footer {
            text-align: center;
            font-size: 12px;
            color: var(--color-text-muted);
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="login-wrapper">

        {{-- Branding --}}
        <div class="login-brand">
            <div class="login-brand-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62
                             48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636
                             0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399
                             5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702
                             50.702 0 0 1 3.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0
                             1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0
                             6.75 15.75v-1.5" />
                </svg>
            </div>
            <div class="login-brand-name">SDN Babakan 02</div>
            <div class="login-brand-sub">Sistem Informasi Sekolah</div>
        </div>

        {{-- Card --}}
        <div class="login-card">

            <h2 class="login-card-title">Masuk ke akun Anda</h2>

            {{-- Alert Error --}}
            @if ($errors->any())
                <div class="alert-error">
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        required
                        placeholder="nama@sekolah.id"
                        class="form-input @error('email') is-error @enderror"
                    >
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                        placeholder="Masukkan password"
                        class="form-input"
                    >
                </div>

                {{-- Remember Me --}}
                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit">
                    Masuk
                </button>

            </form>
        </div>

        <p class="login-footer">
            SDN Babakan 02 &copy; {{ date('Y') }}
        </p>

    </div>

</body>
</html>