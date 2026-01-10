<div class="hero__container">
    @if($heroMovies->count() > 0)
        {{-- Кнопка "Назад" --}}
        @if($heroMovies->currentPage() > 1)
            <a href="{{ $heroMovies->previousPageUrl() }}#hero"
               class="hero__arrow hero__arrow--left hero-pagination-link"
               data-type="{{ $type ?? 'default' }}"
               data-page="{{ $heroMovies->currentPage() - 1 }}">
                &#10094;
            </a>
        @else
            <button class="hero__arrow hero__arrow--left disabled" disabled>&#10094;</button>
        @endif

        <div class="hero__viewport">
            <div class="hero__slider">
                @foreach ($heroMovies as $movie)
                    <div class="hero__slide {{ $loop->first ? 'active' : '' }}">
                        @php
                            $image = $movie->banner
                                ? asset('storage/' . $movie->banner)
                                : asset('assets/img/board/shrek.webp');
                        @endphp
                        <img src="{{ $image }}" class="hero__image" alt="{{ $movie->title }}">
                        <div class="hero__content">
                            <h1 class="hero__title">{{ $movie->title }}</h1>
                            <p class="hero__description">{{ Str::limit($movie->description, 200, '...') }}</p>
                            <a href="{{ route('main.movies.show', $movie->id) }}"
                               class="hero__button">Смотреть</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Кнопка "Вперед" --}}
        @if($heroMovies->hasMorePages())
            <a href="{{ $heroMovies->nextPageUrl() }}#hero"
               class="hero__arrow hero__arrow--right hero-pagination-link"
               data-type="{{ $type ?? 'default' }}"
               data-page="{{ $heroMovies->currentPage() + 1 }}">
                &#10095;
            </a>
        @else
            <button class="hero__arrow hero__arrow--right disabled" disabled>&#10095;</button>
        @endif

        {{-- Пагинация для героя --}}
        <div class="hero__pagination">
            <div class="hero__pagination-wrapper">
                @foreach ($heroMovies->getUrlRange(1, $heroMovies->lastPage()) as $page => $url)
                    <a href="{{ $url }}#hero"
                       class="hero__pagination-item hero-pagination-link {{ $page == $heroMovies->currentPage() ? 'active' : '' }}"
                       data-type="{{ $type ?? 'default' }}"
                       data-page="{{ $page }}">
                        {{ $page }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Текущая позиция --}}
        <div class="hero__position">
            <span class="hero__position-current">{{ $heroMovies->currentPage() }}</span>
            <span class="hero__position-separator">/</span>
            <span class="hero__position-total">{{ $heroMovies->lastPage() }}</span>
        </div>
    @else
        <div class="hero__empty">
            <p>Нет фильмов для показа</p>
        </div>
    @endif
</div>

