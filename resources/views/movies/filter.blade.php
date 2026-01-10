@extends('layouts.main')

@section('title', 'Фильтр фильмов')

@section('content')
    <div class="filter-page">
        <div class="container">
            <h1 class="page-title">Поиск и фильтр фильмов</h1>

            <form method="GET" action="{{ route('main.filter') }}" class="filter-form" id="filter-form">
                <div class="filter-form-block">
                    {{-- Поиск по названию --}}
                    <div class="filter-block">
                        <h3 class="filter-block__title">
                            <i class="fas fa-search"></i> Поиск
                        </h3>
                        <input
                            type="text"
                            name="search"
                            placeholder="Название фильма или описание..."
                            value="{{ $filters['search'] ?? '' }}"
                            class="filter-search"
                        >
                    </div>

                    {{-- Жанры --}}
                    <div class="filter-block">
                        <h3 class="filter-block__title">
                            <i class="fas fa-film"></i> Жанры
                        </h3>
                        <div class="filter-scroll">
                            @foreach($genres as $genre)
                                <label class="filter-checkbox">
                                    <input
                                        type="checkbox"
                                        name="genres[]"
                                        value="{{ $genre->id }}"
                                        {{ isset($filters['genres']) && in_array($genre->id, $filters['genres']) ? 'checked' : '' }}
                                        class="filter-checkbox__input"
                                    >
                                    <span class="filter-checkbox__label">
                                {{ $genre->name }}
                            </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Актёры --}}
                    <div class="filter-block">
                        <h3 class="filter-block__title">
                            <i class="fas fa-users"></i> Актёры
                        </h3>
                        <div class="filter-scroll">
                            @foreach($actors as $actor)
                                <label class="filter-checkbox">
                                    <input
                                        type="checkbox"
                                        name="actors[]"
                                        value="{{ $actor->id }}"
                                        {{ isset($filters['actors']) && in_array($actor->id, $filters['actors']) ? 'checked' : '' }}
                                        class="filter-checkbox__input"
                                    >
                                    <span class="filter-checkbox__label">
                                {{ $actor->name }}
                            </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Год выпуска --}}
                    <div class="filter-row">
                        <div class="filter-block">
                            <h3 class="filter-block__title">
                                <i class="fas fa-calendar"></i> Год от
                            </h3>
                            <input
                                type="number"
                                name="year_from"
                                placeholder="1900"
                                value="{{ $filters['year_from'] ?? '' }}"
                                min="1900"
                                max="{{ date('Y') }}"
                                class="filter-input"
                            >
                        </div>

                        <div class="filter-block">
                            <h3 class="filter-block__title">
                                <i class="fas fa-calendar"></i> Год до
                            </h3>
                            <input
                                type="number"
                                name="year_to"
                                placeholder="{{ date('Y') }}"
                                value="{{ $filters['year_to'] ?? '' }}"
                                min="1900"
                                max="{{ date('Y') }}"
                                class="filter-input"
                            >
                        </div>
                    </div>

                    {{-- Рейтинг --}}
                    <div class="filter-block">
                        <h3 class="filter-block__title">
                            <i class="fas fa-star"></i> Рейтинг от
                        </h3>
                        <div class="rating-slider">
                            <input
                                type="range"
                                name="rating"
                                min="0"
                                max="10"
                                step="0.5"
                                value="{{ $filters['rating'] ?? 0 }}"
                                class="rating-slider__input"
                                id="rating-slider"
                            >
                            <div class="rating-slider__labels">
                                <span>0</span>
                                <span id="rating-value">{{ $filters['rating'] ?? 0 }}</span>
                                <span>10</span>
                            </div>
                        </div>
                    </div>

                    {{-- Сортировка --}}
                    <div class="filter-block">
                        <h3 class="filter-block__title">
                            <i class="fas fa-sort"></i> Сортировка
                        </h3>
                        <select name="sort" class="filter-select">
                            <option value="">По умолчанию (новые)</option>
                            <option value="rating" {{ ($filters['sort'] ?? '') === 'rating' ? 'selected' : '' }}>
                                По рейтингу (высокий → низкий)
                            </option>
                            <option value="year" {{ ($filters['sort'] ?? '') === 'year' ? 'selected' : '' }}>
                                По году (новые → старые)
                            </option>
                            <option value="year_asc" {{ ($filters['sort'] ?? '') === 'year_asc' ? 'selected' : '' }}>
                                По году (старые → новые)
                            </option>
                            <option value="title" {{ ($filters['sort'] ?? '') === 'title' ? 'selected' : '' }}>
                                По названию (А-Я)
                            </option>
                        </select>
                    </div>

                </div>

                {{-- Кнопки --}}
                <div class="filter-actions">
                    <button type="submit" class="btn btn--primary">
                        <i class="fas fa-filter"></i> Применить фильтры
                    </button>
                    <a href="{{ route('main.filter') }}" class="btn btn--secondary">
                        <i class="fas fa-times"></i> Сбросить
                    </a>
                </div>
            </form>

            {{-- Результаты --}}
            <div class="filter-results">
                <div class="filter-results__header">
                    <h2 class="filter-results__title">
                        Найдено фильмов: <span class="filter-results__count">{{ $movies->total() }}</span>
                    </h2>

                    {{-- Активные фильтры --}}
                    @if(count($filters) > 0)
                        <div class="active-filters">
                            <strong>Активные фильтры:</strong>
                            @if(isset($filters['search']))
                                <span class="active-filter">
                                Поиск: "{{ $filters['search'] }}"
                                <a href="{{ route('main.filter', array_merge($filters, ['search' => null])) }}"
                                   class="active-filter__remove">&times;</a>
                            </span>
                            @endif

                            @if(isset($filters['genres']))
                                @foreach($genres->whereIn('id', $filters['genres']) as $genre)
                                    <span class="active-filter">
                                    Жанр: {{ $genre->name }}
                                    <a href="{{ route('main.filter', array_merge($filters, ['genres' => array_diff($filters['genres'], [$genre->id])])) }}"
                                       class="active-filter__remove">&times;</a>
                                </span>
                                @endforeach
                            @endif

                            @if(isset($filters['actors']))
                                @foreach($actors->whereIn('id', $filters['actors']) as $actor)
                                    <span class="active-filter">
                                    Актер: {{ $actor->name }}
                                    <a href="{{ route('main.filter', array_merge($filters, ['actors' => array_diff($filters['actors'], [$actor->id])])) }}"
                                       class="active-filter__remove">&times;</a>
                                </span>
                                @endforeach
                            @endif

                            @if(isset($filters['rating']) && $filters['rating'] > 0)
                                <span class="active-filter">
                                Рейтинг: от {{ $filters['rating'] }}
                                <a href="{{ route('main.filter', array_merge($filters, ['rating' => null])) }}"
                                   class="active-filter__remove">&times;</a>
                            </span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Список фильмов --}}
                @if($movies->count() > 0)
                    <div class="movies-flex">

                            @include('components.movie-card', ['movies' => $movies])

                    </div>

                    {{-- Пагинация --}}
                    @if($movies->hasPages())
                        <div class="pagination-wrapper">
                            {{ $movies->withQueryString()->links() }}
                        </div>
                    @endif
                @else
                    <div class="filter-empty">
                        <i class="fas fa-film filter-empty__icon"></i>
                        <h3 class="filter-empty__title">Фильмы не найдены</h3>
                        <p class="filter-empty__text">Попробуйте изменить параметры поиска</p>
                        <a href="{{ route('main.filter') }}" class="btn btn--primary">
                            Сбросить фильтры
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


