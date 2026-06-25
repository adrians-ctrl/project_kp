<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'label', 'type' => 'text', 'required' => false, 'hint' => null, 'value' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['name', 'label', 'type' => 'text', 'required' => false, 'hint' => null, 'value' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<div class="space-y-1.5">
    <label for="<?php echo e($name); ?>" class="block text-sm font-medium text-[var(--foreground)]">
        <?php echo e($label); ?><?php if($required): ?><span class="text-[var(--destructive)] ml-0.5">*</span><?php endif; ?>
    </label>
    <input type="<?php echo e($type); ?>" id="<?php echo e($name); ?>" name="<?php echo e($name); ?>" <?php echo e($required ? 'required' : ''); ?>

           <?php echo e($attributes->merge(['class' => 'w-full h-9 rounded-md border px-3 text-sm outline-none transition bg-[var(--card)] text-[var(--foreground)] placeholder:text-[var(--muted-foreground)] border-[var(--input)] focus:border-[var(--ring)] focus:ring-2 focus:ring-[var(--ring)]/20 ' . ($errors->has($name) ? 'border-red-400 bg-red-50' : '')])); ?>

           value="<?php echo e(old($name, $value)); ?>">
    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-[var(--destructive)]"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    <?php if($hint && !$errors->has($name)): ?><p class="text-xs text-[var(--muted-foreground)]"><?php echo e($hint); ?></p><?php endif; ?>
</div><?php /**PATH D:\Users\mr015\OneDrive\Dokumen\File Kampus\project_kp-main\project_kp\resources\views/components/ui/input.blade.php ENDPATH**/ ?>