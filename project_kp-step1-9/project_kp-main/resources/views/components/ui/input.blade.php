@props(['name', 'label', 'type' => 'text', 'required' => false, 'hint' => null, 'value' => null])
<div class="space-y-1.5">
    <label for="{{ $name }}" class="block text-sm font-medium text-[var(--foreground)]">
        {{ $label }}@if ($required)<span class="text-[var(--destructive)] ml-0.5">*</span>@endif
    </label>
    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" {{ $required ? 'required' : '' }}
           {{ $attributes->merge(['class' => 'w-full h-9 rounded-md border px-3 text-sm outline-none transition bg-[var(--card)] text-[var(--foreground)] placeholder:text-[var(--muted-foreground)] border-[var(--input)] focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20 ' . ($errors->has($name) ? 'border-red-400 bg-red-50' : '')]) }}
           value="{{ old($name, $value) }}">
    @error($name)<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
    @if ($hint && !$errors->has($name))<p class="text-xs text-[var(--muted-foreground)]">{{ $hint }}</p>@endif
</div>