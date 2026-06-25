<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['tone' => 'neutral']));

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

foreach (array_filter((['tone' => 'neutral']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>
<?php
$s = match($tone) {
    'success'     => 'bg-green-50 text-green-700 ring-green-200',
    'warning'     => 'bg-yellow-50 text-yellow-700 ring-yellow-200',
    'destructive' => 'bg-red-50 text-red-700 ring-red-200',
    'info'        => 'bg-blue-50 text-blue-700 ring-blue-200',
    default       => 'bg-[var(--muted)] text-[var(--muted-foreground)] ring-[var(--border)]',
};
?>
<span <?php echo e($attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {$s}"])); ?>>
    <?php echo e($slot); ?>

</span><?php /**PATH D:\Users\mr015\OneDrive\Dokumen\File Kampus\project_kp-main\project_kp\resources\views/components/ui/badge.blade.php ENDPATH**/ ?>