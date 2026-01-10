@extends('layouts.main')

@section('title', 'Мой профиль')

@section('content')
    <div class="profile-page">
        <div class="container">
            {{-- Заголовок --}}
            <div class="profile-header">
                <div class="profile-avatar">
                    <img src="{{ $user->avatar_url }}"
                         alt="{{ $user->name }}"
                         class="profile-avatar__img">
                    <div class="profile-avatar__edit">
                        <a href="{{ route('main.profile.edit') }}" class="profile-avatar__edit-link">
                            <i class="fas fa-camera"></i>
                        </a>
                    </div>
                </div>

                <div class="profile-info">
                    <h1 class="profile-name">{{ $user->name }}</h1>
                    <p class="profile-email">{{ $user->email }}</p>

                    @if($user->city)
                        <div class="profile-location">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $user->city }}
                        </div>
                    @endif

                    <div class="profile-stats">
                        <div class="profile-stat">
                            <span class="profile-stat__value">{{  $user->comments->count() }}</span>
                            <span class="profile-stat__label">Отзывов</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat__value">{{  $user->likedMovies->count() }}</span>
                            <span class="profile-stat__label">понравившиеся</span>
                        </div>
                        <div class="profile-stat">
                            <span class="profile-stat__value">{{ $user->age ?? '—' }}</span>
                            <span class="profile-stat__label">Возраст</span>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Навигация --}}
            <nav class="profile-nav">
                <a href="{{ route('main.profile.index') }}"
                   class="profile-nav__link {{ request()->routeIs('main.profile.index') ? 'active' : '' }}">
                    <i class="fas fa-user"></i> Профиль
                </a>
                <a href="{{ route('main.profile.likes') }}"  {{-- было favorites --}}
                class="profile-nav__link {{ request()->routeIs('main.profile.likes') ? 'active' : '' }}">
                    <i class="fas fa-heart"></i> Лайки {{-- было Избранное --}}
                </a>
                <a href="{{ route('main.profile.comments.index') }}"  {{-- было reviews --}}
                class="profile-nav__link {{ request()->routeIs('main.profile.comments.index') ? 'active' : '' }}">
                    <i class="fas fa-comment"></i> Мои комментарии {{-- было Мои отзывы --}}
                </a>
                <a href="{{ route('main.profile.edit') }}"
                   class="profile-nav__link {{ request()->routeIs('main.profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Настройки
                </a>
            </nav>

            {{-- О пользователе --}}
            @if($user->about)
                <div class="profile-section">
                    <h2 class="profile-section__title">Обо мне</h2>
                    <p class="profile-about">{{ $user->about }}</p>
                </div>
            @endif

            {{-- Последние комментарии --}}
            @if($user->comments->isNotEmpty())
                <div class="profile-section">
                    <div class="profile-section__header">
                        <h2 class="profile-section__title">Последние комментарии</h2> {{-- было Отзывы --}}
                        <a href="{{ route('main.profile.comments.index') }}" class="profile-section__link"> {{-- было reviews --}}
                            Все комментарии <i class="fas fa-arrow-right"></i> {{-- было Все отзывы --}}
                        </a>
                    </div>

                    <div class="profile-reviews"> {{-- оставляем класс для стилей --}}
                        @foreach($user->comments->take(3) as $comment)
                            <div class="profile-review">
                                <a href="{{ route('main.movies.show', $comment->movie) }}"
                                   class="profile-review__movie">
                                    <img src="{{ $comment->movie->poster ? asset('storage/' . $comment->movie->poster) : asset('assets/img/board/shrek.webp') }}"
                                         alt="{{ $comment->movie->title }}"
                                         class="profile-review__poster">
                                    <div class="profile-review__movie-info">
                                        <h4 class="profile-review__title">{{ $comment->movie->title }}</h4>
                                        @if($comment->rating) {{-- если есть рейтинг в комментариях --}}
                                        <div class="profile-review__rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-star {{ $i * 2 <= $comment->rating ? 'fas' : 'far' }}"></i>
                                            @endfor
                                            <span class="profile-review__rating-value">{{ $comment->rating }}/10</span>
                                        </div>
                                        @endif
                                    </div>
                                </a>
                                <p class="profile-review__comment">{{ Str::limit($comment->comment, 150, '...') }}</p> {{-- было comment --}}
                                <time class="profile-review__date">{{ $comment->created_at->format('d.m.Y') }}</time>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Последние лайки --}}
            @if($user->likedMovies->isNotEmpty())
                <div class="profile-section">
                    <div class="profile-section__header">
                        <h2 class="profile-section__title">Лайки</h2> {{-- было Избранные фильмы --}}
                        <a href="{{ route('main.profile.likes') }}" class="profile-section__link"> {{-- было favorites --}}
                            Все лайки <i class="fas fa-arrow-right"></i> {{-- было Все избранные --}}
                        </a>
                    </div>

                    <div class="profile-favorites">
                        @foreach($user->likedMovies->take(6) as $movie)
                            <a href="{{ route('main.movies.show', $movie) }}" class="profile-favorite">
                                <img src="{{ $movie->poster ? asset('storage/' . $movie->poster) : asset('assets/img/board/shrek.webp') }}"
                                     alt="{{ $movie->title }}"
                                     class="profile-favorite__poster">
                                <div class="profile-favorite__overlay">
                                    <h4 class="profile-favorite__title">{{ $movie->title }}</h4>
                                    <div class="profile-favorite__year">{{ $movie->year }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
