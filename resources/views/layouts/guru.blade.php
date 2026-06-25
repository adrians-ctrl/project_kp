<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SDN Babakan 02</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full bg-[var(--background)] text-[var(--foreground)]" x-data="layoutGuru()">

    <div x-show="sidebarOpen && isMobile" x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
         class="fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

    <div class="flex h-full min-h-screen">

        <aside :class="{
                'translate-x-0': sidebarOpen,
                '-translate-x-full': !sidebarOpen && isMobile,
                'w-64': sidebarOpen || !isMobile,
                'w-0 lg:w-16': !sidebarOpen && !isMobile
            }"
            class="fixed inset-y-0 left-0 z-40 flex flex-col border-r border-[var(--sidebar-border)]
                   bg-[var(--sidebar)] text-[var(--sidebar-foreground)] transition-all duration-300
                   lg:relative lg:translate-x-0">

            <div class="flex h-16 shrink-0 items-center justify-between border-b border-[var(--sidebar-border)] px-4">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-[var(--sidebar-primary)]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[var(--sidebar-primary-foreground)]"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.25">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62
                                     48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0
                                     0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896
                                     .248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0
                                     0 1 3.74-3.342"/>
                        </svg>
                    </div>
                    <div class="flex flex-col leading-tight" :class="{'hidden': !sidebarOpen && !isMobile}">
                        <span class="font-display text-sm font-bold tracking-tight text-[var(--sidebar-foreground)]">SDN Babakan 02</span>
                        <span class="text-[10px] uppercase tracking-[0.14em] text-[var(--sidebar-foreground)]/60">Panel Guru</span>
                    </div>
                </div>
                <button @click="toggleSidebar" type="button"
                        class="ml-auto flex h-8 w-8 items-center justify-center rounded-md
                               text-[var(--sidebar-foreground)]/60 hover:bg-[var(--sidebar-accent)]
                               hover:text-[var(--sidebar-accent-foreground)] transition-colors">
                    <svg x-show="!sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                    <svg x-show="sidebarOpen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto overflow-x-hidden py-5"
                 :class="sidebarOpen || isMobile ? 'px-3' : 'px-2'">
                @include('components.sidebar.guru-nav')
            </nav>

            <div class="shrink-0 border-t border-[var(--sidebar-border)] p-3">
                <div class="flex items-center gap-3 rounded-md px-3 py-2.5 overflow-hidden">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full
                                bg-[var(--sidebar-primary)] text-sm font-semibold text-[var(--sidebar-primary-foreground)]">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1 overflow-hidden" :class="{'hidden': !sidebarOpen && !isMobile}">
                        <div class="truncate text-sm font-medium text-[var(--sidebar-foreground)]">{{ Auth::user()->name }}</div>
                        <div class="truncate text-[11px] text-[var(--sidebar-foreground)]/60">Guru</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="mt-1 flex w-full items-center gap-2 rounded-md px-3 py-2 text-sm
                                   text-[var(--sidebar-foreground)]/80 hover:bg-[var(--sidebar-accent)]
                                   hover:text-[var(--sidebar-accent-foreground)] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25
                                     2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                        </svg>
                        <span :class="{'hidden': !sidebarOpen && !isMobile}">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col transition-all duration-300">
            <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between
                           border-b border-[var(--border)] bg-[var(--card)]/90 px-4 backdrop-blur lg:px-6">
                <button @click="sidebarOpen = true" type="button"
                        class="mr-3 flex h-9 w-9 items-center justify-center rounded-md border
                               border-[var(--border)] bg-[var(--card)] text-[var(--muted-foreground)]
                               hover:bg-[var(--muted)] transition-colors lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>

                <div class="flex items-center gap-1.5 text-sm text-[var(--muted-foreground)] min-w-0">
                    @hasSection('breadcrumb')
                        @yield('breadcrumb')
                    @else
                        <span class="font-medium text-[var(--foreground)]">@yield('page-title', 'Dashboard')</span>
                    @endif
                </div>

                <div class="ml-auto flex items-center gap-2">
                    <span class="hidden text-sm text-[var(--muted-foreground)] sm:block">{{ Auth::user()->name }}</span>
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-[var(--primary)]
                                text-xs font-semibold text-[var(--primary-foreground)]">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden px-4 py-6 lg:px-6 lg:py-8">
                <div class="mx-auto w-full max-w-7xl space-y-6">
                    @if (session('success'))
                        <x-ui.alert type="success" :message="session('success')" />
                    @endif
                    @if (session('error'))
                        <x-ui.alert type="error" :message="session('error')" />
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        function layoutGuru() {
            return {
                sidebarOpen: window.innerWidth >= 1024,
                isMobile: window.innerWidth < 1024,
                init() {
                    this.handleResize();
                    window.addEventListener('resize', () => this.handleResize());
                },
                handleResize() {
                    this.isMobile = window.innerWidth < 1024;
                    if (!this.isMobile) {
                        const saved = localStorage.getItem('sidebarOpenGuru');
                        this.sidebarOpen = saved === null ? true : saved === 'true';
                    } else {
                        this.sidebarOpen = false;
                    }
                },
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                    if (!this.isMobile) localStorage.setItem('sidebarOpenGuru', this.sidebarOpen);
                }
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
