{{-- Карточки фильмов --}}
<div class="movies-grid">
@foreach ($movies as $movie)
    <div class="movie-card">
        <a href="{{ route('main.movies.show', $movie->id) }}" class="movie-card__link">
            <img src="{{ $movie->poster ? asset('storage/' . $movie->poster) : asset('assets/img/board/shrek.webp') }}"
                 alt="{{ $movie->title }}" class="movie-card__image">

            {{-- Hover информация --}}
            <div class="movie-card__hover-info">
                {{-- Год, рейтинг, возраст --}}
                <div class="movie-card__meta">
                    <span class="movie-card__year">{{ $movie->year ?? '—' }}</span>
                    <span class="movie-card__rating">{{ $movie->rating ?? '—' }}/10</span>
                    <span class="movie-card__age">{{ \App\Models\Movie::$ageLabels[$movie->age_rating] ?? $movie->age_rating }}</span>
                </div>

                {{-- Жанры отдельными блоками --}}
                @if($movie->genres->isNotEmpty())
                    <div class="movie-card__genres">
                        @foreach($movie->genres as $genre)
                            <span class="genre-badge">{{ $genre->name }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- Кнопка "Смотреть" --}}
                <a href="{{ route('main.movies.show', $movie->id) }}" class="movie-card__button">
                    Смотреть
                </a>
            </div>
        </a>

        {{-- Название фильма под картинкой --}}
        <h3 class="movie-card__title">{{ $movie->title }}</h3>
    </div>
@endforeach
</div>
{{-- Пагинация --}}
@if(method_exists($movies, 'hasPages') && $movies->hasPages())
    <div class="pagination-wrapper">
        <nav class="pagination">
            {{-- Предыдущая страница --}}
            @if($movies->onFirstPage())
                <span class="pagination-item disabled">&laquo;</span>
            @else
                <a href="{{ $movies->previousPageUrl() }}"
                   class="pagination-item pagination-link"
                   data-type="{{ $type ?? 'default' }}"
                   data-page="{{ $movies->currentPage() - 1 }}">
                    &laquo;
                </a>
            @endif

            {{-- Страницы --}}
            @foreach ($movies->getUrlRange(1, $movies->lastPage()) as $page => $url)
                @if ($page == $movies->currentPage())
                    <span class="pagination-item active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                       class="pagination-item pagination-link"
                       data-type="{{ $type ?? 'default' }}"
                       data-page="{{ $page }}">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Следующая страница --}}
            @if($movies->hasMorePages())
                <a href="{{ $movies->nextPageUrl() }}"
                   class="pagination-item pagination-link"
                   data-type="{{ $type ?? 'default' }}"
                   data-page="{{ $movies->currentPage() + 1 }}">
                    &raquo;
                </a>
            @else
                <span class="pagination-item disabled">&raquo;</span>
            @endif
        </nav>
    </div>
@endif
