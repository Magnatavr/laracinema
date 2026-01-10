<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="/admin" class="brand-link">
            <span class="brand-text fw-light">AdminLTE 4</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column"
                data-lte-toggle="treeview"
                role="navigation"
                id="navigation">

                <li class="nav-item">
                    <a href="{{ route('admin.genres.index') }}" class="nav-link">
                        <i class="nav-icon fa-solid fa-cannabis"></i>
                        <p>Жанры</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.movies.index') }}" class="nav-link">
                        <i class="nav-icon fa-solid fa-film"></i>
                        <p>Фильмы</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.actors.index') }}" class="nav-link">
                        <i class="nav-icon fa-solid fa-person-through-window"></i>
                        <p>Актёры</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link">
                        <i class="fa-solid fa-users"></i>
                        <p>Пользователи</p>
                    </a>
                </li>


            </ul>
        </nav>
    </div>
</aside>
