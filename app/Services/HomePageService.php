<?php

namespace App\Services;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Сервис для работы с главной страницей
 * Управляет получением данных для различных секций главной страницы
 */
class HomePageService
{
    /**
     * Количество фильмов для hero-секции
     */
    private const HERO_MOVIES_COUNT = 5;

    /**
     * Количество фильмов для топ-10
     */
    private const TOP_MOVIES_COUNT = 10;

    /**
     * Элементов на страницу для пагинации
     */
    private const MOVIES_PER_PAGE = 2;

    /**
     * Получает все данные для главной страницы
     *
     * @return array Данные для главной страницы
     */
    public function getHomePageData(): array
    {
        return [
            'heroMovies' => $this->getHeroMovies(),
            'recentMovies' => $this->getRecentMovies(),
            'popularMovies' => $this->getPopularMovies(),
            'topMovies' => $this->getTopMovies(),
        ];
    }

    /**
     * Обрабатывает AJAX запросы для пагинации
     *
     * @param Request $request HTTP запрос
     * @return array Ответ в формате JSON
     * @throws \InvalidArgumentException При неверном типе запроса
     */
    public function handleAjaxPagination(Request $request): array
    {
        $type = $request->get('type');
        $page = $request->get('page', 1);

        $this->validateAjaxRequestType($type);

        $data = $this->getPaginatedData($type, $page);

        return [
            'html' => $this->renderHtml($type, $data),
            'currentPage' => $data->currentPage(),
            'lastPage' => $data->lastPage(),
            'hasMore' => $data->hasMorePages(),
        ];
    }

    /**
     * Получает фильмы для hero-секции
     *
     * @return LengthAwarePaginator
     */
    private function getHeroMovies(): LengthAwarePaginator
    {
        return Movie::whereNotNull('banner')
            ->latest()
            ->take(self::HERO_MOVIES_COUNT)
            ->paginate(1, ['*'], 'hero');
    }

    /**
     * Получает недавно добавленные фильмы
     *
     * @return LengthAwarePaginator
     */
    private function getRecentMovies(): LengthAwarePaginator
    {
        return Movie::latest()
            ->paginate(self::MOVIES_PER_PAGE, ['*'], 'recent_page');
    }

    /**
     * Получает популярные фильмы (по рейтингу)
     *
     * @return LengthAwarePaginator
     */
    private function getPopularMovies(): LengthAwarePaginator
    {
        return Movie::orderByDesc('rating')
            ->paginate(self::MOVIES_PER_PAGE, ['*'], 'popular_page');
    }

    /**
     * Получает топ-10 фильмов по рейтингу
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTopMovies()
    {
        return Movie::orderByDesc('rating')
            ->take(self::TOP_MOVIES_COUNT)
            ->get();
    }

    /**
     * Валидирует тип AJAX запроса
     *
     * @param string $type Тип запроса
     * @throws \InvalidArgumentException
     */
    private function validateAjaxRequestType(string $type): void
    {
        $validTypes = ['hero', 'recent', 'popular'];

        if (!in_array($type, $validTypes)) {
            throw new \InvalidArgumentException('Invalid AJAX request type');
        }
    }

    /**
     * Получает пагинированные данные для указанного типа
     *
     * @param string $type Тип данных
     * @param int $page Номер страницы
     * @return LengthAwarePaginator
     */
    private function getPaginatedData(string $type, int $page): LengthAwarePaginator
    {
        switch ($type) {
            case 'hero':
                return $this->getPaginatedHeroMovies($page);
            case 'recent':
                return $this->getPaginatedRecentMovies($page);
            case 'popular':
                return $this->getPaginatedPopularMovies($page);
            default:
                throw new \InvalidArgumentException("Unknown type: {$type}");
        }
    }

    /**
     * Получает пагинированные hero-фильмы
     *
     * @param int $page Номер страницы
     * @return LengthAwarePaginator
     */
    private function getPaginatedHeroMovies(int $page): LengthAwarePaginator
    {
        return Movie::whereNotNull('banner')
            ->latest()
            ->take(self::HERO_MOVIES_COUNT)
            ->paginate(1, ['*'], 'hero_page', $page);
    }

    /**
     * Получает пагинированные недавние фильмы
     *
     * @param int $page Номер страницы
     * @return LengthAwarePaginator
     */
    private function getPaginatedRecentMovies(int $page): LengthAwarePaginator
    {
        return Movie::latest()
            ->paginate(self::MOVIES_PER_PAGE, ['*'], 'page', $page);
    }

    /**
     * Получает пагинированные популярные фильмы
     *
     * @param int $page Номер страницы
     * @return LengthAwarePaginator
     */
    private function getPaginatedPopularMovies(int $page): LengthAwarePaginator
    {
        return Movie::orderByDesc('rating')
            ->paginate(self::MOVIES_PER_PAGE, ['*'], 'page', $page);
    }

    /**
     * Генерирует HTML для ответа
     *
     * @param string $type Тип данных
     * @param LengthAwarePaginator $data Пагинированные данные
     * @return string HTML
     */
    private function renderHtml(string $type, LengthAwarePaginator $data): string
    {
        $viewName = $type === 'hero' ? 'components.hero-card' : 'components.movie-card';

        return view($viewName, [
            'heroMovies' => $type === 'hero' ? $data : null,
            'movies' => $type !== 'hero' ? $data : null,
            'type' => $type
        ])->render();
    }

    /**
     * Получает рекомендуемые фильмы на основе просмотров пользователя
     * (заглушка для будущей реализации рекомендательной системы)
     *
     * @param int|null $userId ID пользователя
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRecommendedMovies(?int $userId = null)
    {
        // TODO: Реализовать рекомендательную систему
        // Пока возвращаем популярные фильмы
        return Movie::orderByDesc('rating')
            ->take(6)
            ->get();
    }

    /**
     * Получает статистику по фильмам для админ-панели
     *
     * @return array
     */
    public function getMoviesStatistics(): array
    {
        return [
            'total' => Movie::count(),
            'withBanner' => Movie::whereNotNull('banner')->count(),
            'averageRating' => round(Movie::avg('rating') ?? 0, 1),
            'thisMonth' => Movie::whereMonth('created_at', now()->month)->count(),
        ];
    }
}
