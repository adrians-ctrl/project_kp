<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', $profil?->nama_sekolah ?? 'SDN Babakan 02'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta-description', 'Website resmi ' . ($profil?->nama_sekolah ?? 'SDN Babakan 02')); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="h-full bg-[var(--background)] text-[var(--foreground)] flex flex-col min-h-screen" x-data="{ mobileMenu: false }">

    
    <header class="sticky top-0 z-40 border-b border-[var(--border)] bg-[var(--card)]/90 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 lg:px-6">

            
            <a href="<?php echo e(route('landing')); ?>" class="flex items-center gap-3 min-w-0">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[var(--primary)]">
                    <?php if($profil?->logo): ?>
                        <img src="<?php echo e($profil->logo_url); ?>" alt="Logo" class="h-7 w-7 object-contain">
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--primary-foreground)]"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.74-3.342"/>
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="min-w-0 leading-tight">
                    <p class="truncate font-display text-sm font-bold text-[var(--foreground)]">
                        <?php echo e($profil?->nama_sekolah ?? 'SDN Babakan 02'); ?>

                    </p>
                    <p class="truncate text-[11px] text-[var(--muted-foreground)]">
                        <?php echo e($profil?->kota ?? 'Bogor'); ?>

                    </p>
                </div>
            </a>

            
            <nav class="hidden items-center gap-1 lg:flex">
                <?php
                    $navItems = [
                        ['route' => 'landing', 'label' => 'Beranda'],
                        ['route' => 'landing.berita', 'label' => 'Berita'],
                        ['route' => 'landing.galeri', 'label' => 'Galeri'],
                        ['route' => 'landing.kontak', 'label' => 'Kontak'],
                    ];
                ?>
                <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route($item['route'])); ?>"
                       class="rounded-md px-4 py-2 text-sm font-medium transition-colors
                              <?php echo e(request()->routeIs($item['route']) || ($item['route'] === 'landing.berita' && request()->routeIs('landing.berita.detail'))
                                 ? 'bg-[var(--primary-soft)] text-[var(--primary)]'
                                 : 'text-[var(--muted-foreground)] hover:text-[var(--foreground)] hover:bg-[var(--muted)]'); ?>">
                        <?php echo e($item['label']); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </nav>

            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('login')); ?>"
                   class="hidden sm:inline-flex items-center gap-1.5 rounded-md bg-[var(--primary)]
                          px-4 py-2 text-sm font-medium text-[var(--primary-foreground)]
                          hover:opacity-90 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25
                                 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3h-12"/>
                    </svg>
                    Masuk
                </a>

                
                <button @click="mobileMenu = !mobileMenu" type="button"
                        class="flex h-9 w-9 items-center justify-center rounded-md border
                               border-[var(--border)] text-[var(--muted-foreground)] lg:hidden">
                    <svg x-show="!mobileMenu" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg x-show="mobileMenu" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        
        <div x-show="mobileMenu" x-transition
             class="border-t border-[var(--border)] bg-[var(--card)] lg:hidden" style="display:none">
            <nav class="flex flex-col gap-1 px-4 py-3">
                <?php $__currentLoopData = $navItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route($item['route'])); ?>"
                       class="rounded-md px-3 py-2 text-sm font-medium
                              <?php echo e(request()->routeIs($item['route']) ? 'bg-[var(--primary-soft)] text-[var(--primary)]' : 'text-[var(--muted-foreground)]'); ?>">
                        <?php echo e($item['label']); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('login')); ?>"
                   class="mt-1 rounded-md bg-[var(--primary)] px-3 py-2 text-center text-sm font-medium text-[var(--primary-foreground)]">
                    Masuk ke Sistem
                </a>
            </nav>
        </div>
    </header>

    
    <main class="flex-1">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <footer class="border-t border-[var(--border)] bg-[var(--card)]">
        <div class="mx-auto max-w-6xl px-4 py-12 lg:px-6">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">

                
                <div class="sm:col-span-2">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[var(--primary)]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--primary-foreground)]"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.74-3.342"/>
                            </svg>
                        </div>
                        <p class="font-display font-bold text-[var(--foreground)]">
                            <?php echo e($profil?->nama_sekolah ?? 'SDN Babakan 02'); ?>

                        </p>
                    </div>
                    <p class="text-sm text-[var(--muted-foreground)] leading-relaxed max-w-md">
                        <?php echo e($profil?->alamat_lengkap ?? 'Jl. Babakan Raya No. 10, Bogor, Jawa Barat'); ?>

                    </p>
                    <?php if($profil?->npsn): ?>
                        <p class="mt-2 text-xs text-[var(--muted-foreground)]">NPSN: <?php echo e($profil->npsn); ?></p>
                    <?php endif; ?>
                </div>

                
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-[var(--muted-foreground)] mb-3">
                        Tautan
                    </p>
                    <ul class="space-y-2 text-sm">
                        <li><a href="<?php echo e(route('landing')); ?>" class="text-[var(--muted-foreground)] hover:text-[var(--primary)] transition-colors">Beranda</a></li>
                        <li><a href="<?php echo e(route('landing.berita')); ?>" class="text-[var(--muted-foreground)] hover:text-[var(--primary)] transition-colors">Berita</a></li>
                        <li><a href="<?php echo e(route('landing.galeri')); ?>" class="text-[var(--muted-foreground)] hover:text-[var(--primary)] transition-colors">Galeri</a></li>
                        <li><a href="<?php echo e(route('landing.kontak')); ?>" class="text-[var(--muted-foreground)] hover:text-[var(--primary)] transition-colors">Kontak</a></li>
                    </ul>
                </div>

                
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-[var(--muted-foreground)] mb-3">
                        Kontak
                    </p>
                    <ul class="space-y-2 text-sm text-[var(--muted-foreground)]">
                        <?php if($profil?->telepon): ?>
                            <li class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                                </svg>
                                <?php echo e($profil->telepon); ?>

                            </li>
                        <?php endif; ?>
                        <?php if($profil?->email): ?>
                            <li class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                </svg>
                                <span class="truncate"><?php echo e($profil->email); ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="mt-8 border-t border-[var(--border)] pt-6 text-center text-xs text-[var(--muted-foreground)]">
                &copy; <?php echo e(date('Y')); ?> <?php echo e($profil?->nama_sekolah ?? 'SDN Babakan 02'); ?>. Hak cipta dilindungi.
            </div>
        </div>
    </footer>

</body>
</html><?php /**PATH D:\Users\mr015\OneDrive\Dokumen\File Kampus\project_kp-main\project_kp\resources\views/layouts/public.blade.php ENDPATH**/ ?>