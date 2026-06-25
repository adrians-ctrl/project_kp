<?php $__env->startSection('title', 'Berita & Pengumuman — ' . ($profil?->nama_sekolah ?? 'SDN Babakan 02')); ?>

<?php $__env->startSection('content'); ?>

    <section class="border-b border-[var(--border)] bg-[var(--card)] py-10">
        <div class="mx-auto max-w-6xl px-4 lg:px-6">
            <h1 class="font-display text-2xl font-bold text-[var(--foreground)] lg:text-3xl">Berita & Pengumuman</h1>
            <p class="mt-2 text-sm text-[var(--muted-foreground)]">
                Kumpulan berita dan pengumuman resmi dari <?php echo e($profil?->nama_sekolah ?? 'sekolah'); ?>.
            </p>
        </div>
    </section>

    <section class="py-12 lg:py-16">
        <div class="mx-auto max-w-6xl px-4 lg:px-6">

            <?php if($berita->isEmpty()): ?>
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-[var(--muted-foreground)] mb-4"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3z"/>
                    </svg>
                    <p class="text-sm text-[var(--muted-foreground)]">Belum ada berita yang dipublikasikan.</p>
                </div>
            <?php else: ?>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php $__currentLoopData = $berita; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('landing.berita.detail', $item->slug)); ?>"
                           class="group rounded-xl border border-[var(--border)] bg-[var(--card)] overflow-hidden
                                  hover:shadow-[var(--shadow-elevated)] transition-shadow">
                            <div class="aspect-video bg-[var(--muted)] overflow-hidden">
                                <?php if($item->gambar_url): ?>
                                    <img src="<?php echo e($item->gambar_url); ?>" alt="<?php echo e($item->judul); ?>"
                                         class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                <?php else: ?>
                                    <div class="flex h-full items-center justify-center text-[var(--muted-foreground)]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-2">
                                    <?php if (isset($component)) { $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.badge','data' => ['tone' => $item->kategori === 'pengumuman' ? 'warning' : 'info']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tone' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->kategori === 'pengumuman' ? 'warning' : 'info')]); ?>
                                        <?php echo e(ucfirst($item->kategori)); ?>

                                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $attributes = $__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__attributesOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4)): ?>
<?php $component = $__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4; ?>
<?php unset($__componentOriginalab7baa01105b3dfe1e0cf1dfc58879b4); ?>
<?php endif; ?>
                                    <span class="text-[11px] text-[var(--muted-foreground)]"><?php echo e($item->created_at->diffForHumans()); ?></span>
                                </div>
                                <h3 class="font-semibold text-[var(--foreground)] leading-snug line-clamp-2
                                           group-hover:text-[var(--primary)] transition-colors">
                                    <?php echo e($item->judul); ?>

                                </h3>
                                <p class="mt-2 text-sm text-[var(--muted-foreground)] line-clamp-2">
                                    <?php echo e($item->konten_ringkas); ?>

                                </p>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if($berita->hasPages()): ?>
                    <div class="mt-10">
                        <?php echo e($berita->links('components.ui.pagination')); ?>

                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Users\mr015\OneDrive\Dokumen\File Kampus\project_kp-main\project_kp\resources\views/landing/berita.blade.php ENDPATH**/ ?>