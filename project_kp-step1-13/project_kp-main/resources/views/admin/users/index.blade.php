@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('breadcrumb')
    <span class="text-[var(--muted-foreground)]">Admin</span>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[var(--muted-foreground)]/40 mx-0.5"
         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
    </svg>
    <span class="font-medium text-[var(--foreground)]">Manajemen User</span>
@endsection

@section('content')

<div x-data="{ modalTambah: false }">

    <x-ui.page-header
        title="Manajemen User"
        desc="Kelola akun pengguna sistem beserta role dan hak aksesnya."
    >
        <x-slot:actions>
            <x-ui.button @click="modalTambah = true" type="button">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah User
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Tabel --}}
    <x-ui.section-card :noPadding="true">
        <x-slot:actions>
            <span class="text-xs text-[var(--muted-foreground)]">{{ $users->total() }} akun</span>
        </x-slot:actions>

        @if ($users->isEmpty())
            <x-ui.empty-state title="Belum ada akun pengguna" />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-[var(--border)] bg-[var(--muted)]/50">
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">No</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Nama</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Email</th>
                            <th class="px-5 py-3 text-center text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Role</th>
                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Data Guru</th>
                            <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wide text-[var(--muted-foreground)]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        @foreach ($users as $i => $user)
                            <tr class="hover:bg-[var(--muted)]/40 transition-colors"
                                x-data="{ modalReset: false, modalHapus: false }">
                                <td class="px-5 py-3.5 tabular-nums text-[var(--muted-foreground)]">
                                    {{ $users->firstItem() + $i }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center
                                                    rounded-full bg-[var(--primary)] text-xs font-semibold
                                                    text-[var(--primary-foreground)]">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-[var(--foreground)]">
                                                {{ $user->name }}
                                                @if ($user->id === Auth::id())
                                                    <span class="ml-1.5 text-[10px] text-[var(--muted-foreground)]">(Anda)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                    {{ $user->email }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <x-ui.badge :tone="$user->role === 'admin' ? 'warning' : 'info'">
                                        {{ ucfirst($user->role) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-5 py-3.5 text-[var(--muted-foreground)]">
                                    @if ($user->guruStaf)
                                        <div class="flex items-center gap-1.5">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                            <span class="text-sm">{{ $user->guruStaf->nama_lengkap }}</span>
                                        </div>
                                    @else
                                        <span class="text-[var(--muted-foreground)]/60">Belum terhubung</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1">
                                        {{-- Reset password --}}
                                        <button @click="modalReset = true" type="button"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-md
                                                       text-[var(--muted-foreground)] hover:bg-[var(--muted)]
                                                       hover:text-[var(--foreground)] transition-colors"
                                                title="Reset Password">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/>
                                            </svg>
                                        </button>

                                        {{-- Edit --}}
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="inline-flex h-8 w-8 items-center justify-center rounded-md
                                                  text-[var(--muted-foreground)] hover:bg-[var(--muted)]
                                                  hover:text-[var(--foreground)] transition-colors" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z"/>
                                            </svg>
                                        </a>

                                        {{-- Hapus (disabled untuk akun sendiri) --}}
                                        @if ($user->id !== Auth::id())
                                            <button @click="modalHapus = true" type="button"
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-md
                                                           text-[var(--muted-foreground)] hover:bg-red-50
                                                           hover:text-red-600 transition-colors" title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                </svg>
                                            </button>
                                        @else
                                            <span class="inline-flex h-8 w-8 items-center justify-center
                                                         rounded-md text-[var(--muted-foreground)]/30 cursor-not-allowed">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Modal Reset Password --}}
                                    <div x-show="modalReset"
                                         x-transition:enter="transition-opacity duration-200"
                                         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition-opacity duration-200"
                                         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                         @keydown.escape.window="modalReset = false"
                                         class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                         style="display:none">
                                        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                                             @click="modalReset = false"></div>
                                        <div x-show="modalReset"
                                             x-transition:enter="transition duration-200"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             class="relative w-full max-w-sm rounded-xl border border-[var(--border)]
                                                    bg-[var(--card)] shadow-[var(--shadow-elevated)]">
                                            <div class="flex items-center justify-between border-b border-[var(--border)] px-5 py-4">
                                                <div>
                                                    <h2 class="font-display text-base font-semibold text-[var(--foreground)]">Reset Password</h2>
                                                    <p class="text-xs text-[var(--muted-foreground)] mt-0.5">{{ $user->name }}</p>
                                                </div>
                                                <button @click="modalReset = false" type="button"
                                                        class="flex h-8 w-8 items-center justify-center rounded-md
                                                               text-[var(--muted-foreground)] hover:bg-[var(--muted)]">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <form method="POST"
                                                  action="{{ route('admin.users.reset-password', $user) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="space-y-4 px-5 py-5">
                                                    <x-ui.input name="password" label="Password Baru"
                                                        type="password" :required="true"
                                                        placeholder="Minimal 8 karakter" />
                                                    <x-ui.input name="password_confirmation"
                                                        label="Konfirmasi Password" type="password"
                                                        :required="true" placeholder="Ulangi password" />
                                                </div>
                                                <div class="flex items-center justify-end gap-2 border-t border-[var(--border)] px-5 py-4">
                                                    <x-ui.button variant="secondary" type="button" @click="modalReset = false">Batal</x-ui.button>
                                                    <x-ui.button type="submit">Reset Password</x-ui.button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Modal Hapus --}}
                                    <x-ui.confirm-delete
                                        name="modalHapus"
                                        :action="route('admin.users.destroy', $user)"
                                        :label="'akun ' . $user->name"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="border-t border-[var(--border)] px-5 py-4">
                    {{ $users->links('components.ui.pagination') }}
                </div>
            @endif
        @endif
    </x-ui.section-card>

    {{-- Modal Tambah User --}}
    <div x-show="modalTambah"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @keydown.escape.window="modalTambah = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display:none">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="modalTambah = false"></div>
        <div x-show="modalTambah"
             x-transition:enter="transition duration-200"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-md rounded-xl border border-[var(--border)]
                    bg-[var(--card)] shadow-[var(--shadow-elevated)]">
            <div class="flex items-center justify-between border-b border-[var(--border)] px-5 py-4">
                <h2 class="font-display text-base font-semibold text-[var(--foreground)]">Tambah Akun User</h2>
                <button @click="modalTambah = false" type="button"
                        class="flex h-8 w-8 items-center justify-center rounded-md
                               text-[var(--muted-foreground)] hover:bg-[var(--muted)]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="space-y-4 px-5 py-5">
                    <x-ui.input name="name" label="Nama Lengkap" :required="true" placeholder="Nama pengguna" />
                    <x-ui.input name="email" label="Email" type="email" :required="true" placeholder="email@domain.com" />
                    <x-ui.select name="role" label="Role" :required="true"
                        :options="['admin' => 'Admin', 'guru' => 'Guru']" placeholder="Pilih role..." />
                    <x-ui.input name="password" label="Password" type="password" :required="true"
                        placeholder="Minimal 8 karakter" />
                    <x-ui.input name="password_confirmation" label="Konfirmasi Password"
                        type="password" :required="true" placeholder="Ulangi password" />
                    @if ($errors->any())
                        <x-ui.alert type="error" :message="$errors->first()" />
                    @endif
                </div>
                <div class="flex items-center justify-end gap-2 border-t border-[var(--border)] px-5 py-4">
                    <x-ui.button variant="secondary" type="button" @click="modalTambah = false">Batal</x-ui.button>
                    <x-ui.button type="submit">Simpan</x-ui.button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection
