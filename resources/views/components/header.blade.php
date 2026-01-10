<header class="header">
    <div class="header__container">
        {{-- ЛОГОТИП --}}
        <a href="{{ route('main.index') }}" class="header__logo">LaraCinema</a>

        {{-- МЕНЮ ЖАНРОВ --}}
        <nav class="header__nav">
            <ul class="header__menu">
                @foreach($genres as $genre)
                    <li>
                        <a class="header__link {{ isset($currentGenre) && $currentGenre->id == $genre->id ? 'active' : '' }}"
                           href="{{ route('main.genres', $genre->id) }}">
                            {{ mb_convert_case($genre->name, MB_CASE_TITLE, 'UTF-8') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="header__right">
            {{-- ПОИСК --}}
            <div class="header__search-btn-wrapper" data-tooltip="Поиск фильмов">
                <button class="header__search-btn" data-open-search>
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>

            {{-- AUTH --}}
            <div class="header__auth">
                @auth
                    <a href="{{ route('main.profile.index') }}" class="header__link">Профиль</a>
                    @if(Auth::user()->isAdmin())
                        <a class="nav-link" href="{{ route('admin.main.index') }}">
                            <i class="bi bi-speedometer2 me-1"></i> Админка
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="logout-btn">Выйти</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="header__link">Войти</a>
                    <a href="{{ route('register') }}" class="header__link">Регистрация</a>
                @endauth
            </div>
        </div>
    </div>
</header>
