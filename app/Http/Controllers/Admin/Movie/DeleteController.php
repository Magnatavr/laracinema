<?php

namespace App\Http\Controllers\Admin\Movie;

use App\Models\Movie;
use Exception;
use Illuminate\Http\RedirectResponse;
use Log;


/**
 * Контроллер для удаления фильмов
 * Обрабатывает запросы на удаление фильмов и связанных данных
 */
class DeleteController extends BaseController
{

    /**
     * Обрабатывает запрос на удаление фильма
     * Использует автоматическое разрешение модели через маршрут
     *
     * @param Movie $movie Фильм для удаления
     * @return RedirectResponse
     */
    public function __invoke(Movie $movie)
    {
        try {
            // Удаляем фильм через сервис
            $this->movieService->deleteMovie($movie);

            // Возвращаем успешный ответ
            return redirect()->route('admin.movies.index')->with('success', 'Фильм успешно удален.');

        } catch (Exception $e) {
            // Логируем ошибку для отладки
            Log::error('Ошибка при удалении фильма [ID: ' . $movie->id . ']: ' . $e->getMessage());

            // Возвращаем пользователя с сообщением об ошибке
            return back()->withErrors(['error' => 'Произошла ошибка при удалении фильма. Пожалуйста, попробуйте еще раз.']);
        }
    }
}
