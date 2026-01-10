<?php

namespace App\Http\Controllers\Main;

use Illuminate\Http\Request;

/**
 * Контроллер для фильтрации и поиска фильмов
 * Использует MovieFilterService для обработки фильтров
 */
class FilterController extends BaseController
{

    /**
     * Обрабатывает запрос на фильтрацию фильмов
     * Возвращает страницу с отфильтрованными результатами
     *
     * @param Request $request HTTP запрос с параметрами фильтрации
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        try {
            // Получаем отфильтрованные данные через сервис
            $data = $this->filterService->filterMovies($request);

            // Определяем есть ли активные фильтры для отображения сообщения
            $hasActiveFilters = $this->filterService->hasActiveFilters($data['filters']);

            return view('movies.filter', array_merge($data, [
                'hasActiveFilters' => $hasActiveFilters,
            ]));

        } catch (\Exception $e) {
            // Логируем ошибку для отладки
            \Log::error('Ошибка при фильтрации фильмов: ' . $e->getMessage());

            // Возвращаем пользователя с сообщением об ошибке
            return back()
                ->withErrors(['error' => 'Произошла ошибка при фильтрации. Пожалуйста, попробуйте еще раз.'])
                ->withInput();
        }
    }

    /**
     * Сбрасывает фильтры и перенаправляет на страницу фильтрации
     *
     * @param Request $request HTTP запрос
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetFilters(Request $request)
    {
        // Получаем текущие фильтры
        $currentFilters = $request->except(['_token', 'reset']);

        // Определяем какие фильтры сбросить
        $filtersToReset = array_keys($currentFilters);

        // Сбрасываем фильтры через сервис
        $newFilters = $this->filterService->resetFilters($currentFilters, $filtersToReset);

        // Перенаправляем с очищенными фильтрами
        return redirect()->route('movies.filter', $newFilters);
    }
}
