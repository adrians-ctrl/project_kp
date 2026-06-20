<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SDN Babakan 02</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[var(--background)] text-[var(--foreground)]">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: true, isMobile: window.innerWidth < 768 }"
         @resize.window="isMobile = window.innerWidth < 768">
        <aside :class="(sidebarOpen || isMobile) ? 'w-56' : 'w-16'"
               class="bg-[var(--sidebar)] text-[var(--sidebar-foreground)] flex flex-col transition-all duration-200 shrink-0">
            <div class="flex h-16 items-center gap-3 px-4 border-b border-[var(--sidebar-border)] overflow-hidden">
                <div class="h-8 w-8 shrink-0 rounded-md bg-[var(--sidebar-primary)] flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[var(--sidebar-primary-foreground)]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.74-3.342"/>
                    </svg>
                </div>
                <div :class="{'opacity-0 w-0 overflow-hidden': !sidebarOpen && !isMobile}" class="transition-all duration-200">
                    <div class="text-xs font-bold text-[var(--sidebar-foreground)] whitespace-nowrap">SDN Babakan 02</div>
                    <div class="text-[10px] text-[var(--sidebar-foreground)]/60 uppercase tracking-wider whitespace-nowrap">Panel Guru</div>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" type="button"
                        class="ml-auto shrink-0 flex h-7 w-7 items-center justify-center rounded-md
                               text-[var(--sidebar-foreground)]/60 hover:bg-[var(--sidebar-accent)] transition-colors"
                        :class="{'hidden': isMobile}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 p-3 overflow-y-auto overflow-x-hidden">
                <x-sidebar.guru-nav />
            </nav>

            <div class="p-3 border-t border-[var(--sidebar-border)]">
                <div class="flex items-center gap-3 px-3 py-2 overflow-hidden">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full
                                bg-[var(--sidebar-primary)] text-[10px] font-semibold
                                text-[var(--sidebar-primary-foreground)]">
                        {{ Auth::user()->inisial }}
                    </div>
                    <div :class="{'opacity-0 w-0 overflow-hidden': !sidebarOpen && !isMobile}"
                         class="transition-all duration-200 min-w-0">
                        <p class="text-xs font-medium text-[var(--sidebar-foreground)] truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-[var(--sidebar-foreground)]/60 truncate">Guru</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="mt-1 w-full flex items-center gap-3 rounded-md px-3 py-2 text-sm
                                   text-[var(--sidebar-foreground)]/80 hover:bg-[var(--sidebar-accent)] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/>
                        </svg>
                        <span :class="{'opacity-0 w-0 overflow-hidden': !sidebarOpen && !isMobile}"
                              class="transition-all duration-200">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-x-hidden px-6 py-8">
            <div class="mx-auto max-w-7xl space-y-6">
                @if (session('success'))<x-ui.alert type="success" :message="session('success')" />@endif
                @if (session('error'))<x-ui.alert type="error" :message="session('error')" />@endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>