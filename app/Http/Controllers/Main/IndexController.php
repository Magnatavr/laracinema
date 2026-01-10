<?php

namespace App\Http\Controllers\Main;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;
use Log;

/**
 * Контроллер главной страницы
 * Управляет отображением главной страницы и обработкой AJAX запросов
 */
class IndexController extends BaseController
{

    /**
     * Обрабатывает запросы на главную страницу
     * Поддерживает как обычные запросы, так и AJAX для пагинации
     *
     * @param Request $request HTTP запрос
     * @return View|JsonResponse
     */
    public function __invoke(Request $request)
    {
        // Обработка AJAX запросов для пагинации
        if ($this->isAjaxPaginationRequest($request)) {
            return $this->handleAjaxPagination($request);
        }

        // Обычный запрос - загружаем полную страницу
        return $this->renderHomePage();
    }

    /**
     * Проверяет является ли запрос AJAX запросом для пагинации
     *
     * @param Request $request HTTP запрос
     * @return bool
     */
    private function isAjaxPaginationRequest(Request $request): bool
    {
        return $request->ajax() && $request->has('paginate') && $request->has('type');
    }

    /**
     * Обрабатывает AJAX запрос для пагинации
     *
     * @param Request $request HTTP запрос
     * @return JsonResponse
     */
    private function handleAjaxPagination(Request $request): JsonResponse
    {
        try {
            $data = $this->homeService->handleAjaxPagination($request);

            return response()->json($data);

        } catch (InvalidArgumentException $e) {
            // Некорректный тип запроса
            return response()->json(['error' => $e->getMessage()], 400);

        } catch (Exception $e) {
            // Внутренняя ошибка сервера
            Log::error('AJAX pagination error: ' . $e->getMessage());

            return response()->json(['error' => 'Произошла ошибка при загрузке данных. Пожалуйста, попробуйте еще раз.'], 500);
        }
    }

    /**
     * Отображает главную страницу
     *
     * @return View
     */
    private function renderHomePage(): View
    {
        try {
            // Получаем все данные для главной страницы через сервис
            $data = $this->homeService->getHomePageData();

            // Добавляем дополнительные данные если нужно
            $data['recommended'] = $this->homeService->getRecommendedMovies(auth()->id());

            return view('home', $data);

        } catch (Exception $e) {
            // Логируем ошибку
            Log::error('Home page error: ' . $e->getMessage());

            // Возвращаем страницу с базовыми данными
            return view('home', ['heroMovies' => collect(), 'recentMovies' => collect(), 'popularMovies' => collect(), 'topMovies' => collect(), 'error' => 'Произошла ошибка при загрузке данных.',]);
        }
    }
}
