<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>
    </form>
    @php
        $navUser = auth()->user();
        $userName = $navUser?->name ?? 'Pengguna';
        $avatarUrl = $navUser?->avatar;
        $initials = '';

        if ($navUser && $userName) {
            $nameParts = preg_split('/\s+/', trim($userName));
            $initials = collect($nameParts)
                ->filter()
                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode('');
        }
    @endphp

    <ul class="navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <div class="d-sm-none d-lg-inline-flex align-items-center">
                    @if($navUser)
                        <span class="user-avatar mr-2" style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;overflow:hidden;background:#6c5ce7;color:#fff;font-weight:600;">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" alt="{{ $userName }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <span class="initials" style="font-size:0.9rem;">{{ $initials ?: 'P' }}</span>
                            @endif
                        </span>
                    @endif
                    {{ $userName }}
                </div>
            </a>
            @auth
                <div class="dropdown-menu dropdown-menu-right">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-fw fa-power-off"></i> Keluar
                        </button>
                    </form>
                </div>
            @endauth
        </li>
    </ul>
</nav>
