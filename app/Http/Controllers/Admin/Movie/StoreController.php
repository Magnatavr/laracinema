<?php

namespace App\Http\Controllers\Admin\Movie;

use App\Http\Requests\Admin\Movie\StoreRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Log;

/**
 * Контроллер для создания новых фильмов
 * Обрабатывает загрузку медиафайлов и привязку связей
 */
class StoreController extends BaseController
{

    /**
     * Обрабатывает запрос на создание нового фильма
     *
     * @param StoreRequest $request Валидированный запрос
     * @return RedirectResponse
     */
    public function __invoke(StoreRequest $request)
    {
        try {
            // Получаем валидированные данные из запроса
            $data = $request->validated();

            // Собираем файлы из запроса
            $files = $this->extractFilesFromRequest($request);

            // Получаем ID жанров и актеров
            $genreIds = $request->input('genres', []);
            $actorIds = $request->input('actors', []);

            // Создаем фильм через сервис
            $this->movieService->createMovie($data, $files, $genreIds, $actorIds);

            // Возвращаем успешный ответ
            return redirect()->route('admin.movies.index')->with('success', 'Фильм успешно добавлен!');

        } catch (Exception $e) {
            // Логируем ошибку для отладки
            Log::error('Ошибка при создании фильма: ' . $e->getMessage());

            // Возвращаем пользователя с сообщением об ошибке
            return back()->withInput()->withErrors(['error' => 'Произошла ошибка при создании фильма. Пожалуйста, попробуйте еще раз.']);
        }
    }

}
