<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="">
                ADMIN PANEL
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <i class="fas fa-fw fa-code"></i>
        </div>

        @inject('skpiService', \App\Services\AlumniSkpiService::class)
        @php
            $user = auth()->user();
            $alumniActivitiesLocked = ! ($user && optional($user->alumniProfile)->validasi);
            $showSkpiMenu = $user && $skpiService->canViewSkpiPage($user);
            $activeSkpiMasterContent = \App\Models\SkpiMasterContent::ensureActive();

            $alumniItems = [
                [
                    'label' => 'Dashboard',
                    'icon' => 'home',
                    'route' => route('dashboard'),
                ],
                [
                    'label' => 'Profil Alumni',
                    'icon' => 'user',
                    'route' => route('alumni.profile.edit'),
                ],
                [
                    'label' => 'Aktivitas Alumni',
                    'icon' => 'calendar-check',
                    'route' => route('alumni.activities.index'),
                    'disabled' => $alumniActivitiesLocked,
                ],
            ];

            if ($showSkpiMenu) {
                $alumniItems[] = [
                    'label' => 'Ajukan SKPI',
                    'icon' => 'file-alt',
                    'route' => route('alumni.skpi.index'),
                ];
            }

            $sections = [
                [
                    'role' => 'super_admin',
                    'title' => 'Menu Super Admin',
                    'items' => [
                        [
                            'label' => 'Dashboard',
                            'icon' => 'home',
                            'route' => route('dashboard'),
                        ],
                        [
                            'label' => 'Kelola User',
                            'icon' => 'users',
                            'route' => route('admin.users.index'),
                        ],
                    ],
                ],
                [
                    'role' => 'admin',
                    'title' => 'Menu Admin',
                    'items' => [
                        [
                            'label' => 'Dashboard',
                            'icon' => 'home',
                            'route' => route('admin.dashboard'),
                        ],
                        [
                            'label' => 'Data Alumni',
                            'icon' => 'users',
                            'route' => route('admin.validation.requests'),
                        ],
                        [
                            'label' => 'Struktur SKPI',
                            'icon' => 'cog',
                            'route' => route('admin.skpi-master.edit', $activeSkpiMasterContent),
                        ],
                    ],
                ],
                [
                    'role' => 'pimpinan',
                    'title' => 'Menu Pimpinan',
                    'items' => [
                        [
                            'label' => 'Dashboard',
                            'icon' => 'home',
                            'route' => route('dashboard'),
                        ],
                        [
                            'label' => 'Pengajuan SKPI',
                            'icon' => 'file-alt',
                            'route' => route('admin.skpi.submissions'),
                        ],
                    ],
                ],
                [
                    'role' => 'alumni',
                    'title' => 'Menu Alumni',
                    'items' => $alumniItems,
                ],
            ];
        @endphp

        <!-- Menu -->
        <ul class="sidebar-menu">
            @foreach ($sections as $section)
                @if ($user && $user->hasRole($section['role']) && count($section['items']))
                    <li class="menu-header">{{ $section['title'] }}</li>
                    @foreach ($section['items'] as $item)
                        @php
                            $isDisabled = $item['disabled'] ?? false;
                        @endphp
                        <li>
                            <a class="nav-link {{ $isDisabled ? 'disabled text-muted' : '' }}"
                               href="{{ $isDisabled ? 'javascript:void(0)' : $item['route'] }}"
                               aria-disabled="{{ $isDisabled ? 'true' : 'false' }}">
                                <i class="fas fa-{{ $item['icon'] }}"></i> <span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            @endforeach
        </ul>
        <!-- End Menu -->
    </aside>
</div>
