<?php

namespace App\Http\Controllers\Admin\Main;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminDashboardService;


/**
 * Контроллер панели управления
 * Отображает дашборд с аналитикой и статистикой
 */
class IndexController extends Controller
{
    /**
     * Сервис статистики дашборда
     *
     * @var AdminDashboardService
     */
    private $dashboardService;

    /**
     * Конструктор контроллера
     *
     * @param AdminDashboardService $dashboardService Сервис статистики
     */
    public function __construct(AdminDashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Отображает главную страницу панели управления
     *
     * @return \Illuminate\View\View
     */
    public function __invoke()
    {
        $widgetStats = $this->dashboardService->getWidgetStats();
        $fullStats = $this->dashboardService->getDashboardStats();

        return view('admin.main.index', [
            'widgets' => $widgetStats,
            'stats' => $fullStats,
            'pageTitle' => 'Панель управления',
        ]);
    }

    /**
     * Возвращает JSON данные для виджетов (для AJAX обновления)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWidgetsData()
    {
        $widgets = $this->dashboardService->getWidgetStats();

        return response()->json([
            'success' => true,
            'widgets' => $widgets,
            'updated_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Возвращает данные для графика роста
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGrowthData()
    {
        $stats = $this->dashboardService->getDashboardStats();

        return response()->json([
            'success' => true,
            'months' => $stats['monthlyGrowth'] ?? [],
            'labels' => array_column($stats['monthlyGrowth'] ?? [], 'month'),
            'movies' => array_column($stats['monthlyGrowth'] ?? [], 'movies'),
            'actors' => array_column($stats['monthlyGrowth'] ?? [], 'actors'),
            'comments' => array_column($stats['monthlyGrowth'] ?? [], 'comments'),
            'users' => array_column($stats['monthlyGrowth'] ?? [], 'users'),
        ]);
    }
}
