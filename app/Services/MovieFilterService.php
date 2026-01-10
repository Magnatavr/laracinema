<?php

namespace App\Services;

use App\Models\Movie;
use App\Models\Genre;
use App\Models\Actor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Сервис для фильтрации и поиска фильмов
 * Инкапсулирует всю логику фильтрации, поиска и сортировки
 */
class MovieFilterService
{
    /**
     * Фильтры по умолчанию
     */
    private const DEFAULT_PER_PAGE = 12;

    /**
     * Доступные варианты сортировки
     */
    private const SORT_OPTIONS = [
        'rating' => ['field' => 'rating', 'direction' => 'desc'],
        'year' => ['field' => 'year', 'direction' => 'desc'],
        'title' => ['field' => 'title', 'direction' => 'asc'],
        'latest' => ['field' => 'created_at', 'direction' => 'desc'],
    ];

    /**
     * Применяет фильтры к запросу фильмов
     *
     * @param Request $request HTTP запрос с параметрами фильтрации
     * @return array Массив с результатами: фильмы, жанры, актеры, активные фильтры
     */
    public function filterMovies(Request $request): array
    {
        // Получаем данные для фильтров
        $genres = $this->getFilterGenres();
        $actors = $this->getFilterActors();

        // Строим запрос с учетом фильтров
        $query = $this->buildFilterQuery($request);

        // Применяем сортировку
        $query = $this->applySorting($query, $request->get('sort'));

        // Пагинируем результаты
        $movies = $this->paginateResults($query, $request);

        return [
            'movies' => $movies,
            'genres' => $genres,
            'actors' => $actors,
            'filters' => $request->all(),
            'sortOptions' => array_keys(self::SORT_OPTIONS),
            'currentSort' => $request->get('sort', 'latest'),
        ];
    }

    /**
     * Получает список жанров для фильтра
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getFilterGenres()
    {
        return Genre::orderBy('name')->get(['id', 'name']);
    }

    /**
     * Получает список актеров для фильтра
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getFilterActors()
    {
        return Actor::orderBy('name')->get(['id', 'name']);
    }

    /**
     * Строит запрос с применением всех фильтров
     *
     * @param Request $request Запрос с параметрами
     * @return Builder
     */
    private function buildFilterQuery(Request $request): Builder
    {
        $query = Movie::with(['genres', 'actors']);

        // Фильтр по жанрам
        $this->applyGenreFilter($query, $request);

        // Фильтр по актерам
        $this->applyActorFilter($query, $request);

        // Фильтр по году выпуска
        $this->applyYearFilter($query, $request);

        // Фильтр по рейтингу
        $this->applyRatingFilter($query, $request);

        // Поиск по названию и описанию
        $this->applySearchFilter($query, $request);

        return $query;
    }

    /**
     * Применяет фильтр по жанрам
     *
     * @param Builder $query Запрос
     * @param Request $request Запрос с параметрами
     */
    private function applyGenreFilter(Builder $query, Request $request): void
    {
        if ($request->has('genres') && is_array($request->genres) && !empty($request->genres)) {
            $query->whereHas('genres', function(Builder $q) use ($request) {
                $q->whereIn('genres.id', $request->genres);
            });
        }
    }

    /**
     * Применяет фильтр по актерам
     *
     * @param Builder $query Запрос
     * @param Request $request Запрос с параметрами
     */
    private function applyActorFilter(Builder $query, Request $request): void
    {
        if ($request->has('actors') && is_array($request->actors) && !empty($request->actors)) {
            $query->whereHas('actors', function(Builder $q) use ($request) {
                $q->whereIn('actors.id', $request->actors);
            });
        }
    }

    /**
     * Применяет фильтр по году выпуска
     *
     * @param Builder $query Запрос
     * @param Request $request Запрос с параметрами
     */
    private function applyYearFilter(Builder $query, Request $request): void
    {
        if ($request->filled('year_from')) {
            $query->where('year', '>=', (int) $request->year_from);
        }

        if ($request->filled('year_to')) {
            $query->where('year', '<=', (int) $request->year_to);
        }
    }

    /**
     * Применяет фильтр по рейтингу
     *
     * @param Builder $query Запрос
     * @param Request $request Запрос с параметрами
     */
    private function applyRatingFilter(Builder $query, Request $request): void
    {
        if ($request->filled('rating')) {
            $query->where('rating', '>=', (float) $request->rating);
        }
    }

    /**
     * Применяет поиск по названию и описанию
     *
     * @param Builder $query Запрос
     * @param Request $request Запрос с параметрами
     */
    private function applySearchFilter(Builder $query, Request $request): void
    {
        if ($request->filled('search')) {
            $searchTerm = $this->sanitizeSearchTerm($request->search);

            $query->where(function(Builder $q) use ($searchTerm) {
                $q->where('title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }
    }

    /**
     * Очищает поисковый запрос от опасных символов
     *
     * @param string $term Поисковый запрос
     * @return string Очищенный запрос
     */
    private function sanitizeSearchTerm(string $term): string
    {
        return trim(strip_tags($term));
    }

    /**
     * Применяет сортировку к запросу
     *
     * @param Builder $query Запрос
     * @param string|null $sortOption Опция сортировки
     * @return Builder
     */
    private function applySorting(Builder $query, ?string $sortOption): Builder
    {
        $sortConfig = self::SORT_OPTIONS[$sortOption] ?? self::SORT_OPTIONS['latest'];

        return $query->orderBy($sortConfig['field'], $sortConfig['direction']);
    }

    /**
     * Пагинирует результаты запроса
     *
     * @param Builder $query Запрос
     * @param Request $request Запрос с параметрами
     * @return LengthAwarePaginator
     */
    private function paginateResults(Builder $query, Request $request): LengthAwarePaginator
    {
        $perPage = $request->get('per_page', self::DEFAULT_PER_PAGE);
        $perPage = max(1, min(100, (int) $perPage)); // Ограничиваем от 1 до 100

        return $query->paginate($perPage)->appends($request->query());
    }

    /**
     * Проверяет, есть ли активные фильтры
     *
     * @param array $filters Активные фильтры
     * @return bool
     */
    public function hasActiveFilters(array $filters): bool
    {
        // Исключаем служебные параметры пагинации и сортировки
        $excluded = ['page', 'sort', 'per_page'];

        foreach ($filters as $key => $value) {
            if (!in_array($key, $excluded) && !empty($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Сбрасывает указанные фильтры
     *
     * @param array $currentFilters Текущие фильтры
     * @param array $filtersToReset Фильтры для сброса
     * @return array Новый набор фильтров
     */
    public function resetFilters(array $currentFilters, array $filtersToReset = []): array
    {
        foreach ($filtersToReset as $filter) {
            unset($currentFilters[$filter]);
        }

        return $currentFilters;
    }
}
