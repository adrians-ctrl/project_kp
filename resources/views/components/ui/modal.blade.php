{{--
    Komponen: Modal
    Props:
      - name  (string) — nama unik modal, dipakai sebagai Alpine variable
      - title (string) — judul modal
      - size  (string) — 'sm' | 'md' | 'lg' | 'xl'

    Cara pakai:
      <x-ui.modal name="tambahSiswa" title="Tambah Siswa">
          ... konten form ...

          <x-slot:footer>
              <x-ui.button type="submit" form="form-tambah-siswa">Simpan</x-ui.button>
          </x-slot:footer>
      </x-ui.modal>

      Trigger: <x-ui.button @click="tambahSiswa = true">Tambah</x-ui.button>
--}}

@props([
    'name',
    'title',
    'size'  => 'md',
])

@php
$maxWidth = match($size) {
    'sm' => 'max-w-sm',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
    default => 'max-w-lg',
};
@endphp

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
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-title-{{ $name }}"
>
    {{-- Backdrop --}}
    <div
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        @click="{{ $name }} = false"
    ></div>

    {{-- Panel --}}
    <div
        x-show="{{ $name }}"
        x-transition:enter="transition duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full {{ $maxWidth }} rounded-xl border border-[var(--border)]
               bg-[var(--card)] shadow-[var(--shadow-elevated)]"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-[var(--border)] px-5 py-4">
            <h2 id="modal-title-{{ $name }}"
                class="font-display text-base font-semibold tracking-tight text-[var(--foreground)]">
                {{ $title }}
            </h2>
            <button
                @click="{{ $name }} = false"
                type="button"
                class="flex h-8 w-8 items-center justify-center rounded-md
                       text-[var(--muted-foreground)] hover:bg-[var(--muted)]
                       transition-colors"
                aria-label="Tutup modal"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="px-5 py-5">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        @isset($footer)
            <div class="flex items-center justify-end gap-2 border-t
                        border-[var(--border)] px-5 py-4">
                <x-ui.button variant="secondary" @click="{{ $name }} = false">
                    Batal
                </x-ui.button>
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
