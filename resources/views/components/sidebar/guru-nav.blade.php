{{--
    Sidebar navigation untuk panel Guru.
--}}

@php
$navGroups = [
    [
        'label' => 'Umum',
        'items' => [
            [
                'route' => 'guru.dashboard',
                'label' => 'Dashboard',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2
                                   2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0
                                   011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
            ],
        ],
    ],
    [
        'label' => 'Akademik',
        'items' => [
            [
                'route' => 'guru.absensi.index',
                'label' => 'Absensi Kelas',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9
                                   0 0118 0z"/>',
            ],
            [
                'route' => 'guru.nilai.index',
                'label' => 'Input Nilai',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0
                                   01-1.125-1.125M3.375 19.5h1.5C5.496 19.5
                                   6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5
                                   c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625
                                   m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125
                                   -1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75
                                   h-1.5A1.125 1.125 0 0118 18.375"/>',
            ],
            [
                'route' => 'guru.rekap.index',
                'label' => 'Rekap',
                'icon'  => '<path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5
                                   A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0
                                   00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5
                                   2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0
                                   .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504
                                   1.125-1.125V11.25a9 9 0 00-9-9z"/>',
            ],
        ],
    ],
];
@endphp

@foreach ($navGroups as $group)
    <div class="mb-5 last:mb-0">
        <div class="mb-1.5 px-3 text-[10px] font-semibold uppercase
                    tracking-[0.14em] text-[var(--sidebar-foreground)]/50
                    transition-opacity duration-200 overflow-hidden whitespace-nowrap"
             :class="{'opacity-0 h-0 mb-0 overflow-hidden': !sidebarOpen && !isMobile}">
            {{ $group['label'] }}
        </div>

        <ul class="space-y-0.5">
            @foreach ($group['items'] as $item)
                @php
                    $active = request()->routeIs($item['route']) ||
                              str_starts_with(request()->route()?->getName() ?? '', rtrim($item['route'], '.index'));
                @endphp
                <li>
                    <a
                        href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 rounded-md px-3 py-2 text-sm
                               transition-colors group relative
                               {{ $active
                                  ? 'bg-[var(--sidebar-primary)] text-[var(--sidebar-primary-foreground)] font-medium'
                                  : 'text-[var(--sidebar-foreground)]/80 hover:bg-[var(--sidebar-accent)] hover:text-[var(--sidebar-accent-foreground)]'
                               }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-4 w-4 shrink-0"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            {!! $item['icon'] !!}
                        </svg>
                        <span class="truncate transition-opacity duration-200"
                              :class="{'opacity-0 w-0 overflow-hidden': !sidebarOpen && !isMobile}">
                            {{ $item['label'] }}
                        </span>
                        <span class="pointer-events-none absolute left-full ml-2 whitespace-nowrap
                                     rounded-md bg-[var(--foreground)] px-2 py-1 text-xs
                                     text-[var(--background)] opacity-0 shadow-elevated
                                     group-hover:opacity-100 transition-opacity duration-150 z-50"
                              :class="{'hidden': sidebarOpen || isMobile}">
                            {{ $item['label'] }}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endforeach
