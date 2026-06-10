{{--
    Komponen: Form Select
    Props:
      - name        (string)          — name attribute
      - label       (string)          — label teks
      - options     (array)           — ['value' => 'label', ...] atau Collection
      - placeholder (string, optional)— teks opsi kosong
      - required    (bool)
--}}

@props([
    'name',
    'label',
    'options'     => [],
    'placeholder' => 'Pilih...',
    'required'    => false,
])

<div class="space-y-1.5">
    <label for="{{ $name }}"
           class="block text-sm font-medium text-[var(--foreground)]">
        {{ $label }}
        @if ($required)
            <span class="text-[var(--destructive)] ml-0.5">*</span>
        @endif
    </label>

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'w-full h-9 rounded-md border px-3 text-sm outline-none transition
                        bg-[var(--card)] text-[var(--foreground)]
                        border-[var(--input)] focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20
                        ' . ($errors->has($name) ? 'border-[var(--destructive)] bg-[var(--destructive)]/5' : '')
        ]) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach ($options as $value => $label)
            <option value="{{ $value }}"
                    {{ old($name, '') == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="text-xs text-[var(--destructive)]">{{ $message }}</p>
    @enderror
</div>
