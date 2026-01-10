@php use App\Models\Movie; @endphp
@extends('layouts.main')

@section('title', $movie->title)

@section('content')
    <main class="movie-single mt-5">
        <div class="movie-single__container container">
            {{-- Верхняя часть: постер и информация --}}
            <div class="movie-single__hero">
                <div class="movie-single__poster">
                    <img
                        src="{{ $movie->poster ? asset('storage/' . $movie->poster) : asset('assets/img/board/shrek.webp') }}"
                        alt="{{ $movie->title }}"
                        class="movie-single__poster-img">
                </div>

                <div class="movie-single__info">
                    <h1 class="movie-single__title">{{ $movie->title }}</h1>

                    {{-- Мета данные --}}
                    <div class="movie-single__meta">
                        <span class="movie-single__year">{{ $movie->year ?? '—' }}</span>

                        @if($movie->duration)
                            <span class="movie-single__duration" title="Длительность фильма">
                       <i class="far fa-clock"></i>
            {{ App\Helpers\MovieHelper::formatDuration($movie->duration) }}
                               </span>
                        @endif
                        <span
                            class="movie-single__age">{{ Movie::$ageLabels[$movie->age_rating] ?? $movie->age_rating }}</span>
                        @if($movie->genres->isNotEmpty())
                            <div class="movie-single__genres">
                                @foreach($movie->genres as $genre)
                                    <span class="movie-single__genre">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Рейтинг --}}
                    <div class="movie-single__rating">
                        <div class="movie-single__stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-star {{ $i * 2 <= $movie->rating ? 'fas' : 'far' }}"></i>
                            @endfor
                        </div>
                        <span class="movie-single__rating-value">{{ $movie->rating ? number_format($movie->rating, 1) : '—' }}/10</span>
                    </div>

                    {{-- Описание --}}
                    <div class="movie-single__description">
                        <h3>Описание</h3>
                        <p>{{ $movie->description }}</p>
                    </div>

                    {{-- Кнопки действий --}}
                    <div class="movie-single__actions">
                        @if($movie->url)
                            <a href="#player" class="movie-single__watch-btn btn-primary">
                                <i class="fas fa-play"></i> Смотреть фильм
                            </a>
                        @endif

                        @if($movie->trailer_url)
                            <button class="movie-single__trailer-btn btn-secondary" data-open-trailer>
                                <i class="fas fa-film"></i> Смотреть трейлер
                            </button>
                        @endif
                            @auth
                                <button class=" like-btn"
                                        data-movie-id="{{ $movie->id }}"
                                        data-like-url="{{ route('main.profile.likes.toggle', $movie) }}"
                                        data-is-liked="{{ auth()->user()->hasLiked($movie->id) ? 'true' : 'false' }}">
                                    <i class="{{ auth()->user()->hasLiked($movie->id) ? 'fas' : 'far' }} fa-heart"></i>
                                    <span class="like-count">{{ $movie->likes_count ?? $movie->likes()->count() }}</span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn--secondary">
                                    <i class="far fa-heart"></i>
                                    <span class="like-count">{{ $movie->likes_count ?? $movie->likes()->count() }}</span>
                                </a>
                            @endauth
                    </div>
                </div>
            </div>

            {{-- Основной плеер --}}
            @if($movie->url)
                <div class="movie-single__player" id="player">
                    <h2 class="section-title">Просмотр фильма</h2>
                    <div class="movie-single__video-wrapper">
                        <video class="movie-single__video" controls
                               poster="{{ $movie->poster ? asset('storage/' . $movie->poster) : asset('assets/img/board/shrek.webp') }}">
                            <source src="{{ asset('storage/' . $movie->url) }}" type="video/mp4">
                            Ваш браузер не поддерживает видео.
                        </video>
                    </div>
                </div>
            @endif

            {{-- Модальное окно трейлера --}}
            @if($movie->trailer_url)
                <div class="movie-trailer-modal" id="trailerModal">
                    <div class="movie-trailer-modal__overlay" data-close-trailer></div>
                    <div class="movie-trailer-modal__content">
                        <button class="movie-trailer-modal__close" data-close-trailer>
                            <i class="fas fa-times"></i>
                        </button>
                        <video class="movie-trailer-modal__video" controls>
                            <source src="{{ asset('storage/' . $movie->trailer_url) }}" type="video/mp4">
                            Ваш браузер не поддерживает видео
                        </video>
                    </div>
                </div>
            @endif

            {{-- Отзывы --}}
            <section class="movie-reviews">
                <div class="container">
                    <h2 class="section-title">Отзывы</h2>

                    @auth
                        @include('movies.partials.review-form', ['movie' => $movie])
                    @else
                        <div class="movie-reviews__auth-prompt">
                            <p>
                                <a href="{{ route('login') }}" class="movie-reviews__login-link">Войдите</a>,
                                чтобы оставить отзыв
                            </p>
                        </div>
                    @endauth

                    <div class="movie-reviews__list">
                        @forelse($reviews as $review)
                            <article class="movie-review">
                                <div class="movie-review__header">
                                    <div class="movie-review__user">
                                        <div class="movie-review__avatar">
                                            {{ substr($review->user->name, 0, 1) }}
                                        </div>
                                        <div class="movie-review__user-info">
                                            <h4 class="movie-review__username">{{ $review->user->name }}</h4>
                                            <time
                                                class="movie-review__date">{{ $review->created_at->format('d.m.Y H:i') }}</time>
                                        </div>
                                    </div>
                                    <div class="movie-review__rating">
                                        <span class="movie-review__rating-value">{{ $review->rating }}/10</span>
                                        <div class="movie-review__rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fa-star {{ $i * 2 <= $review->rating ? 'fas' : 'far' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <div class="movie-review__body">
                                    <p class="movie-review__comment">{{ $review->comment }}</p>
                                </div>
                            </article>
                        @empty
                            <div class="movie-reviews__empty">
                                <p>Пока нет отзывов. Будьте первым!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
