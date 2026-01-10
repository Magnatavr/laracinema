<?php

namespace App\Services\Admin;

use App\Models\Actor;
use App\Models\Comment;
use App\Models\Genre;
use App\Models\Movie;
use App\Models\User;

/**
 * Сервис для сбора статистики админ-панели
 * Предоставляет данные для виджетов и графиков дашборда
 */
class AdminDashboardService
{
    /**
     * Получает общую статистику для виджетов дашборда
     *
     * @return array Статистические данные
     */
    public function getDashboardStats(): array
    {
        return [
            'movies' => $this->getMoviesStats(),
            'actors' => $this->getActorsStats(),
            'genres' => $this->getGenresStats(),
            'users' => $this->getUsersStats(),
            'comments' => $this->getCommentsStats(),
            'recentActivity' => $this->getRecentActivity(),
            'monthlyGrowth' => $this->getMonthlyGrowth(),
            'topMovies' => $this->getTopRatedMovies(),
            'systemHealth' => $this->getSystemHealth(),
        ];
    }

    /**
     * Статистика по фильмам
     *
     * @return array
     */
    private function getMoviesStats(): array
    {
        $total = Movie::count();
        $withBanners = Movie::whereNotNull('banner')->count();
        $withVideos = Movie::whereNotNull('url')->count();
        $thisMonth = Movie::whereMonth('created_at', now()->month)->count();
        $avgRating = round(Movie::avg('rating') ?? 0, 1);

        // Фильм с самым высоким рейтингом
        $topMovie = Movie::orderByDesc('rating')->first();

        return [
            'total' => $total,
            'withBanners' => $withBanners,
            'withVideos' => $withVideos,
            'thisMonth' => $thisMonth,
            'avgRating' => $avgRating,
            'topMovie' => $topMovie ? [
                'title' => $topMovie->title,
                'rating' => $topMovie->rating,
                'id' => $topMovie->id,
            ] : null,
        ];
    }

    /**
     * Статистика по актерам
     *
     * @return array
     */
    private function getActorsStats(): array
    {
        $total = Actor::count();
        $withPhoto = Actor::whereNotNull('photo')->count();
        $thisMonth = Actor::whereMonth('created_at', now()->month)->count();

        // Самый популярный актер (по количеству фильмов)
        $mostPopularActor = Actor::withCount('movies')
            ->orderByDesc('movies_count')
            ->first();

        // Актер с наибольшим количеством фильмов в этом месяце
        $recentTopActor = Actor::whereHas('movies', function ($query) {
            $query->whereMonth('movies.created_at', now()->month);
        })
            ->withCount('movies')
            ->orderByDesc('movies_count')
            ->first();

        return [
            'total' => $total,
            'withPhoto' => $withPhoto,
            'thisMonth' => $thisMonth,
            'mostPopular' => $mostPopularActor ? [
                'name' => $mostPopularActor->name,
                'movies_count' => $mostPopularActor->movies_count,
                'id' => $mostPopularActor->id,
            ] : null,
            'recentTop' => $recentTopActor ? [
                'name' => $recentTopActor->name,
                'recent_movies_count' => $recentTopActor->movies_count,
                'id' => $recentTopActor->id,
            ] : null,
        ];
    }

    /**
     * Статистика по жанрам
     *
     * @return array
     */
    private function getGenresStats(): array
    {
        $total = Genre::count();

        // Самый популярный жанр (по количеству фильмов)
        $mostPopularGenre = Genre::withCount('movies')
            ->orderByDesc('movies_count')
            ->first();

        // Распределение фильмов по жанрам (топ 5)
        $genresDistribution = Genre::withCount('movies')
            ->orderByDesc('movies_count')
            ->limit(5)
            ->get()
            ->map(function ($genre) {
                return [
                    'name' => $genre->name,
                    'movies_count' => $genre->movies_count,
                    'percentage' => $genre->movies_count > 0 ?
                        round(($genre->movies_count / Movie::count()) * 100, 1) : 0,
                ];
            });

        return [
            'total' => $total,
            'mostPopular' => $mostPopularGenre ? [
                'name' => $mostPopularGenre->name,
                'movies_count' => $mostPopularGenre->movies_count,
                'id' => $mostPopularGenre->id,
            ] : null,
            'distribution' => $genresDistribution,
        ];
    }

    /**
     * Статистика по пользователям
     *
     * @return array
     */
    private function getUsersStats(): array
    {
        $total = User::count();
        $thisMonth = User::whereMonth('created_at', now()->month)->count();
        $activeToday = User::whereDate('created_at', today())->count();
        $activeThisWeek = User::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();

        // Самый активный пользователь (по количеству комментариев)
        $mostActiveUser = User::withCount('comments')
            ->orderByDesc('comments_count')
            ->first();

        return [
            'total' => $total,
            'thisMonth' => $thisMonth,
            'activeToday' => $activeToday,
            'activeThisWeek' => $activeThisWeek,
            'mostActive' => $mostActiveUser ? [
                'name' => $mostActiveUser->name,
                'comments_count' => $mostActiveUser->comments_count,
                'id' => $mostActiveUser->id,
            ] : null,
        ];
    }

    /**
     * Статистика по комментариям
     *
     * @return array
     */
    private function getCommentsStats(): array
    {
        $total = Comment::count();
        $thisMonth = Comment::whereMonth('created_at', now()->month)->count();
        $today = Comment::whereDate('created_at', today())->count();
        $avgRating = round(Comment::avg('rating') ?? 0, 1);

        // Фильм с наибольшим количеством комментариев
        $mostCommentedMovie = Movie::withCount('comments')
            ->orderByDesc('comments_count')
            ->first();

        // Пользователи с лучшими оценками
        $topRaters = User::whereHas('comments')
            ->withAvg('comments', 'rating')
            ->orderByDesc('comments_avg_rating')
            ->limit(5)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'avg_rating' => round($user->comments_avg_rating, 1),
                    'comments_count' => $user->comments_count ?? 0,
                ];
            });

        return [
            'total' => $total,
            'thisMonth' => $thisMonth,
            'today' => $today,
            'avgRating' => $avgRating,
            'mostCommentedMovie' => $mostCommentedMovie ? [
                'title' => $mostCommentedMovie->title,
                'comments_count' => $mostCommentedMovie->comments_count,
                'id' => $mostCommentedMovie->id,
            ] : null,
            'topRaters' => $topRaters,
        ];
    }

    /**
     * Недавняя активность (последние 10 действий)
     *
     * @return array
     */
    private function getRecentActivity(): array
    {
        $activities = [];

        // Новые фильмы
        $recentMovies = Movie::latest()->limit(3)->get();
        foreach ($recentMovies as $movie) {
            $activities[] = [
                'type' => 'movie',
                'icon' => 'film',
                'color' => 'primary',
                'title' => 'Добавлен новый фильм',
                'description' => $movie->title,
                'time' => $movie->created_at->diffForHumans(),
                'link' => route('admin.movies.edit', $movie),
            ];
        }

        // Новые комментарии
        $recentComments = Comment::with('user', 'movie')->latest()->limit(3)->get();
        foreach ($recentComments as $comment) {
            $activities[] = [
                'type' => 'comment',
                'icon' => 'chat-text',
                'color' => 'success',
                'title' => 'Новый комментарий',
                'description' => "{$comment->user->name} оценил \"{$comment->movie->title}\" на {$comment->rating}/10",
                'time' => $comment->created_at->diffForHumans(),
            ];
        }

        // Новые пользователи
        $recentUsers = User::latest()->limit(2)->get();
        foreach ($recentUsers as $user) {
            $activities[] = [
                'type' => 'user',
                'icon' => 'person',
                'color' => 'warning',
                'title' => 'Новый пользователь',
                'description' => $user->name . ' (' . $user->email . ')',
                'time' => $user->created_at->diffForHumans(),
                'link' => route('admin.users.edit', $user),
            ];
        }

        // Сортируем по времени
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 10);
    }

    /**
     * Рост контента по месяцам
     *
     * @return array
     */
    private function getMonthlyGrowth(): array
    {
        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $months[] = [
                'month' => $date->translatedFormat('F Y'),
                'movies' => Movie::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'actors' => Actor::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'comments' => Comment::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
                'users' => User::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
            ];
        }

        return $months;
    }

    /**
     * Топ фильмов по рейтингу
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getTopRatedMovies()
    {
        return Movie::orderByDesc('rating')
            ->withCount('comments')
            ->limit(10)
            ->get()
            ->map(function ($movie) {
                return [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'rating' => $movie->rating,
                    'comments_count' => $movie->comments_count,
                    'year' => $movie->year,
                    'poster_url' => $movie->poster ? asset('storage/' . $movie->poster) : null,
                ];
            });
    }

    /**
     * Статус системы
     *
     * @return array
     */
    private function getSystemHealth(): array
    {
        $totalMovies = Movie::count();
        $moviesWithoutBanner = Movie::whereNull('banner')->count();
        $moviesWithoutPoster = Movie::whereNull('poster')->count();
        $actorsWithoutPhoto = Actor::whereNull('photo')->count();

        // Фильмы без жанров
        $moviesWithoutGenres = Movie::doesntHave('genres')->count();

        // Фильмы без актеров
        $moviesWithoutActors = Movie::doesntHave('actors')->count();

        // Старые бэкапы (старше 7 дней)
        $oldBackups = 0; // Здесь можно добавить логику проверки бэкапов

        // Ошибки в логах (за последние 24 часа)
        $errorCount = 0; // Здесь можно добавить проверку логов

        return [
            'movies_without_banner' => $moviesWithoutBanner,
            'movies_without_poster' => $moviesWithoutPoster,
            'actors_without_photo' => $actorsWithoutPhoto,
            'movies_without_genres' => $moviesWithoutGenres,
            'movies_without_actors' => $moviesWithoutActors,
            'issues' => [
                'critical' => $moviesWithoutPoster > 0 ? 1 : 0,
                'warning' => $moviesWithoutBanner > 0 || $actorsWithoutPhoto > 0 ? 1 : 0,
                'info' => $moviesWithoutGenres > 0 || $moviesWithoutActors > 0 ? 1 : 0,
            ],
        ];
    }

    /**
     * Быстрая статистика для виджетов
     *
     * @return array
     */
    public function getWidgetStats(): array
    {
        return [
            [
                'title' => 'Всего фильмов',
                'value' => Movie::count(),
                'icon' => 'film',
                'color' => 'primary',
                'change' => $this->getMonthlyChange(Movie::class),
                'link' => route('admin.movies.index'),
            ],
            [
                'title' => 'Всего актеров',
                'value' => Actor::count(),
                'icon' => 'person-video',
                'color' => 'success',
                'change' => $this->getMonthlyChange(Actor::class),
                'link' => route('admin.actors.index'),
            ],
            [
                'title' => 'Всего комментариев',
                'value' => Comment::count(),
                'icon' => 'chat-text',
                'color' => 'info',
                'change' => $this->getMonthlyChange(Comment::class),
            ],
            [
                'title' => 'Всего пользователей',
                'value' => User::count(),
                'icon' => 'people',
                'color' => 'warning',
                'change' => $this->getMonthlyChange(User::class),
                'link' => route('admin.users.index'),
            ],
            [
                'title' => 'Средний рейтинг',
                'value' => round(Movie::avg('rating') ?? 0, 1),
                'icon' => 'star',
                'color' => 'danger',
                'change' => $this->getRatingChange(),
                'link' => route('admin.movies.index'),
            ],
            [
                'title' => 'Активных сегодня',
                'value' => User::whereDate('created_at', today())->count(),
                'icon' => 'activity',
                'color' => 'secondary',
                'change' => $this->getDailyActiveChange(),
                'link' => route('admin.users.index'),
            ],
        ];
    }

    /**
     * Процент изменения за месяц
     *
     * @param string $modelClass Класс модели
     * @return array
     */
    private function getMonthlyChange(string $modelClass): array
    {
        $currentMonth = $modelClass::whereMonth('created_at', now()->month)->count();
        $lastMonth = $modelClass::whereMonth('created_at', now()->subMonth()->month)->count();

        if ($lastMonth == 0) {
            $percent = $currentMonth > 0 ? 100 : 0;
        } else {
            $percent = round((($currentMonth - $lastMonth) / $lastMonth) * 100, 1);
        }

        return [
            'percent' => $percent,
            'trend' => $currentMonth >= $lastMonth ? 'up' : 'down',
            'value' => abs($currentMonth - $lastMonth),
        ];
    }

    /**
     * Изменение среднего рейтинга
     *
     * @return array
     */
    private function getRatingChange(): array
    {
        $currentMonthAvg = Movie::whereMonth('created_at', now()->month)->avg('rating') ?? 0;
        $lastMonthAvg = Movie::whereMonth('created_at', now()->subMonth()->month)->avg('rating') ?? 0;

        $change = round($currentMonthAvg - $lastMonthAvg, 1);

        return [
            'percent' => $lastMonthAvg > 0 ? round(($change / $lastMonthAvg) * 100, 1) : 0,
            'trend' => $change >= 0 ? 'up' : 'down',
            'value' => abs($change),
        ];
    }
    /**
     * Изменение активных пользователей
     *
     * @return array
     */
    private function getDailyActiveChange(): array
    {
        $today = User::whereDate('created_at', today())->count();
        $yesterday = User::whereDate('created_at', today()->subDay())->count();

        if ($yesterday == 0) {
            $percent = $today > 0 ? 100 : 0;
        } else {
            $percent = round((($today - $yesterday) / $yesterday) * 100, 1);
        }

        return [
            'percent' => $percent,
            'trend' => $today >= $yesterday ? 'up' : 'down',
            'value' => abs($today - $yesterday),
        ];
    }
}
