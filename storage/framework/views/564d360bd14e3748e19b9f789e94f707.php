<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'href' => null]));

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

foreach (array_filter((['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'href' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
$v = match($variant) {
    'secondary' => 'border border-[var(--border)] bg-[var(--card)] text-[var(--foreground)] hover:bg-[var(--muted)]',
    'danger'    => 'bg-[var(--destructive)] text-white hover:opacity-90',
    'ghost'     => 'text-[var(--muted-foreground)] hover:bg-[var(--muted)] hover:text-[var(--foreground)]',
    default     => 'bg-[var(--primary)] text-[var(--primary-foreground)] hover:opacity-90',
};
$sz = match($size) {
    'sm' => 'h-8 px-3 text-xs gap-1.5',
    'lg' => 'h-11 px-6 text-base gap-2.5',
    default => 'h-9 px-4 text-sm gap-2',
};
$base = "inline-flex items-center justify-center rounded-md font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--ring)] disabled:opacity-50 disabled:pointer-events-none {$v} {$sz}";
?>
<?php if($href): ?>
    <a href="<?php echo e($href); ?>" <?php echo e($attributes->merge(['class' => $base])); ?>><?php echo e($slot); ?></a>
<?php else: ?>
    <button type="<?php echo e($type); ?>" <?php echo e($attributes->merge(['class' => $base])); ?>><?php echo e($slot); ?></button>
<?php endif; ?><?php /**PATH D:\Users\mr015\OneDrive\Dokumen\File Kampus\project_kp-main\project_kp\resources\views/components/ui/button.blade.php ENDPATH**/ ?>