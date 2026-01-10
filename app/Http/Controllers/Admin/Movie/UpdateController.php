<?php

namespace App\Http\Controllers\Admin\Movie;

use App\Http\Requests\Admin\Movie\UpdateRequest;
use App\Models\Movie;
use Exception;
use Illuminate\Http\RedirectResponse;
use Log;


/**
 * Контроллер для обновления существующих фильмов
 * Обрабатывает обновление данных, файлов и связей
 */
class UpdateController extends BaseController
{
    /**
     * Обрабатывает запрос на обновление фильма
     *
     * @param UpdateRequest $request Валидированный запрос
     * @param Movie $movie Фильм для обновления
     * @return RedirectResponse
     */
    public function __invoke(UpdateRequest $request, Movie $movie)
    {
        try {
            // Получаем валидированные данные из запроса
            $data = $request->validated();

            // Собираем файлы из запроса
            $files = $this->extractFilesFromRequest($request);

            // Получаем ID жанров и актеров
            $genreIds = $request->input('genres', []);
            $actorIds = $request->input('actors', []);

            // Обновляем фильм через сервис
            $this->movieService->updateMovie($movie, $data, $files, $genreIds, $actorIds);

            // Возвращаем успешный ответ
            return redirect()->route('admin.movies.index')->with('success', 'Фильм успешно обновлен!');

        } catch (Exception $e) {
            // Логируем ошибку для отладки
            Log::error('Ошибка при обновлении фильма [ID: ' . $movie->id . ']: ' . $e->getMessage());

            // Возвращаем пользователя с сообщением об ошибке
            return back()->withInput()->withErrors(['error' => 'Произошла ошибка при обновлении фильма. Пожалуйста, попробуйте еще раз.']);
        }
    }


}
