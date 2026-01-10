<?php

namespace App\Http\Controllers\Admin\Actor;

use App\Http\Requests\Admin\Actor\StoreRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Log;


/**
 * Контроллер для создания новых актеров
 * Использует инъекцию зависимостей для сервиса актеров
 */
class StoreController extends BaseController
{
    /**
     * Обрабатывает запрос на создание нового актера
     * Использует магический метод __invoke для вызова как функцию
     *
     * @param StoreRequest $request Валидированный запрос
     * @return RedirectResponse
     */
    public function __invoke(StoreRequest $request)
    {
        try {
            // Получаем валидированные данные из запроса
            $data = $request->validated();

            // Получаем файл фотографии если он был загружен
            $photo = $request->hasFile('photo') ? $request->file('photo') : null;

            // Создаем актера через сервис
            $this->actorService->createActor($data, $photo);

            // Возвращаем успешный ответ с сообщением
            return redirect()->route('admin.actors.index')->with('success', 'Актер успешно создан');

        } catch (Exception $e) {
            // Логируем ошибку для дальнейшего анализа
            Log::error('Ошибка при создании актера: ' . $e->getMessage());

            // Возвращаем пользователя с сообщением об ошибке
            return back()->withInput()->withErrors(['error' => 'Произошла ошибка при создании актера. Пожалуйста, попробуйте еще раз.']);
        }
    }
}
