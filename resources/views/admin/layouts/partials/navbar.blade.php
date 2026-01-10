<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">

            <li class="nav-item">
                <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
                </a>
            </li>

            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Гость' }}</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-primary">
                        <img src="{{ auth()->user()?->avatar ? asset(auth()->user()->avatar) : asset('dist/img/user-default.png') }}"
                             class="rounded-circle shadow"
                             alt="User Image">

                        <p>
                            {{ auth()->user()->name ?? 'Гость' }}
                            <small>Member since {{ auth()->user()?->created_at?->format('M Y') ?? 'N/A' }}</small>
                        </p>
                    </li>
                </ul>
            </li>

        </ul>
    </div>
</nav>
