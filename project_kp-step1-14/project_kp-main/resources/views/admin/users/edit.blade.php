@extends('layouts.admin')

@section('title', 'Edit User')

@section('breadcrumb')
    <a href="{{ route('admin.users.index') }}"
       class="text-[var(--muted-foreground)] hover:text-[var(--foreground)] transition-colors">
        Manajemen User
    </a>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Edit</span>
@endsection

@section('content')

    <x-ui.page-header
        title="Edit User"
        desc="Perbarui data akun {{ $user->name }}."
        :back="route('admin.users.index')"
    />

    <x-ui.section-card title="Informasi Akun" class="max-w-lg">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <x-ui.input name="name" label="Nama Lengkap" :required="true"
                :value="old('name', $user->name)" />

            <x-ui.input name="email" label="Email" type="email" :required="true"
                :value="old('email', $user->email)" />

            <div class="space-y-1.5">
                <label class="block text-sm font-medium text-[var(--foreground)]">
                    Role <span class="text-[var(--destructive)] ml-0.5">*</span>
                </label>
                @if ($user->id === Auth::id())
                    <div class="flex h-9 items-center rounded-md border border-[var(--border)]
                                bg-[var(--muted)] px-3 text-sm text-[var(--muted-foreground)]">
                        {{ ucfirst($user->role) }}
                        <span class="ml-2 text-xs">(tidak dapat mengubah role sendiri)</span>
                    </div>
                    <input type="hidden" name="role" value="{{ $user->role }}">
                @else
                    <select name="role" required
                            class="w-full h-9 rounded-md border border-[var(--input)] bg-[var(--card)]
                                   px-3 text-sm outline-none transition text-[var(--foreground)]
                                   focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20">
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="guru"  {{ old('role', $user->role) === 'guru'  ? 'selected' : '' }}>Guru</option>
                    </select>
                @endif
                @error('role')
                    <p class="text-xs text-[var(--destructive)]">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <p class="text-sm font-medium text-[var(--foreground)]">Ganti Password</p>
                <p class="text-xs text-[var(--muted-foreground)]">
                    Kosongkan jika tidak ingin mengganti password.
                </p>
            </div>

            <x-ui.input name="password" label="Password Baru" type="password"
                placeholder="Minimal 8 karakter" />
            <x-ui.input name="password_confirmation" label="Konfirmasi Password Baru"
                type="password" placeholder="Ulangi password baru" />

            <div class="flex items-center gap-3 border-t border-[var(--border)] pt-5">
                <x-ui.button type="submit">Simpan Perubahan</x-ui.button>
                <x-ui.button variant="secondary" href="{{ route('admin.users.index') }}">Batal</x-ui.button>
            </div>
        </form>
    </x-ui.section-card>

@endsection
