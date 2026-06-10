{{--
    Komponen: Konfirmasi Hapus
    Props:
      - name    (string) — nama unik, harus matching dengan Alpine state
      - action  (string) — URL form DELETE
      - label   (string) — nama item yang akan dihapus

    Cara pakai:
      <x-ui.confirm-delete name="hapusSiswa" :action="route('admin.siswa.destroy', $siswa)" label="siswa ini" />

      Trigger: <button @click="hapusSiswa = true">Hapus</button>
--}}

@props([
    'name',
    'action',
    'label' => 'data ini',
])

<div
    x-data="{ {{ $name }}: false }"
    x-show="{{ $name }}"
    x-transition:enter="transition-opacity duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="{{ $name }} = false"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none"
    role="alertdialog"
    aria-modal="true"
>
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"
         @click="{{ $name }} = false"></div>

    <div
        x-show="{{ $name }}"
        x-transition:enter="transition duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative w-full max-w-sm rounded-xl border border-[var(--border)]
               bg-[var(--card)] p-6 shadow-[var(--shadow-elevated)]"
    >
        <div class="flex h-12 w-12 items-center justify-center rounded-full
                    bg-[var(--destructive)]/10 mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-6 w-6 text-[var(--destructive)]"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0
                         2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697
                         16.126zM12 15.75h.007v.008H12v-.008z"/>
            </svg>
        </div>

        <h3 class="text-center font-display text-base font-semibold
                   text-[var(--foreground)] mb-2">
            Konfirmasi Hapus
        </h3>
        <p class="text-center text-sm text-[var(--muted-foreground)] mb-6">
            Tindakan ini akan menghapus <strong class="text-[var(--foreground)]">{{ $label }}</strong>
            secara permanen dan tidak dapat dibatalkan.
        </p>

        <div class="flex gap-3">
            <button
                @click="{{ $name }} = false"
                type="button"
                class="flex-1 h-9 rounded-md border border-[var(--border)]
                       bg-[var(--card)] text-sm font-medium text-[var(--foreground)]
                       hover:bg-[var(--muted)] transition-colors"
            >
                Batal
            </button>

            <form method="POST" action="{{ $action }}" class="flex-1">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="w-full h-9 rounded-md bg-[var(--destructive)] text-sm
                           font-medium text-white hover:opacity-90 transition-opacity"
                >
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
