<?php

namespace App\Http\Controllers\Admin\Actor;

use App\Http\Requests\Admin\Actor\UpdateRequest;
use App\Models\Actor;
use Exception;
use Illuminate\Http\RedirectResponse;
use Log;

/**
 * Контроллер для обновления существующих актеров
 * Обрабатывает запросы на редактирование данных актеров
 */
class UpdateController extends BaseController
{
    /**
     * Обрабатывает запрос на обновление актера
     *
     * @param UpdateRequest $request Валидированный запрос
     * @param Actor $actor Актер для обновления (автоматическое разрешение модели)
     * @return RedirectResponse
     */
    public function __invoke(UpdateRequest $request, Actor $actor)
    {
        try {
            // Получаем валидированные данные из запроса
            $data = $request->validated();

            // Получаем файл фотографии если он был загружен
            $photo = $request->hasFile('photo') ? $request->file('photo') : null;

            // Обновляем актера через сервис
            $this->actorService->updateActor($actor, $data, $photo);

            // Возвращаем успешный ответ с сообщением
            return redirect()->route('admin.actors.index')->with('success', 'Актер успешно обновлен');

        } catch (Exception $e) {
            // Логируем ошибку для дальнейшего анализа
            Log::error('Ошибка при обновлении актера [ID: ' . $actor->id . ']: ' . $e->getMessage());

            // Возвращаем пользователя с сообщением об ошибке
            return back()->withInput()->withErrors(['error' => 'Произошла ошибка при обновлении актера. Пожалуйста, попробуйте еще раз.']);
        }
    }
}
