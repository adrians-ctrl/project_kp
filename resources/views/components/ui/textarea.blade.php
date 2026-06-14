@props(['name', 'label', 'rows' => 4, 'required' => false, 'hint' => null])
<div class="space-y-1.5">
    <label for="{{ $name }}" class="block text-sm font-medium text-[var(--foreground)]">
        {{ $label }}@if ($required)<span class="text-[var(--destructive)] ml-0.5">*</span>@endif
    </label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" {{ $required ? 'required' : '' }}
              {{ $attributes->merge(['class' => 'w-full rounded-md border px-3 py-2 text-sm outline-none transition resize-y bg-[var(--card)] text-[var(--foreground)] placeholder:text-[var(--muted-foreground)] border-[var(--input)] focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20']) }}>{{ old($name) }}</textarea>
    @error($name)<p class="text-xs text-[var(--destructive)]">{{ $message }}</p>@enderror
    @if ($hint && !$errors->has($name))<p class="text-xs text-[var(--muted-foreground)]">{{ $hint }}</p>@endif
</div>