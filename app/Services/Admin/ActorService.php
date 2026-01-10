<?php

namespace App\Services\Admin;

use App\Models\Actor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Сервис для работы с актерами
 * Инкапсулирует бизнес-логику создания, обновления и удаления актеров
 */
class ActorService
{
    /**
     * Создает нового актера с загрузкой фотографии
     *
     * @param array $data Валидированные данные актера
     * @param UploadedFile|null $photo Файл фотографии
     * @return Actor Созданный актер
     */
    public function createActor(array $data, ?UploadedFile $photo = null): Actor
    {
        return DB::transaction(function () use ($data, $photo) {
            // Загружаем фото если оно было передано
            if ($photo) {
                $data['photo'] = $this->uploadPhoto($photo);
            }

            // Создаем актера
            $actor = Actor::create($data);

            return $actor;
        });
    }

    /**
     * Обновляет данные актера
     *
     * @param Actor $actor Актер для обновления
     * @param array $data Новые данные
     * @param UploadedFile|null $photo Новая фотография
     * @return Actor Обновленный актер
     */
    public function updateActor(Actor $actor, array $data, ?UploadedFile $photo = null): Actor
    {
        return DB::transaction(function () use ($actor, $data, $photo) {
            // Обновляем фотографию если была передана новая
            if ($photo) {
                $this->deleteOldPhoto($actor->photo);
                $data['photo'] = $this->uploadPhoto($photo);
            }

            // Обновляем данные актера
            $actor->update($data);

            return $actor->fresh();
        });
    }

    /**
     * Удаляет актера и его фотографию
     *
     * @param Actor $actor Актер для удаления
     * @return bool Результат удаления
     */
    public function deleteActor(Actor $actor): bool
    {
        return DB::transaction(function () use ($actor) {
            // Удаляем фотографию
            $this->deleteOldPhoto($actor->photo);

            // Удаляем актера
            return $actor->delete();
        });
    }

    /**
     * Загружает фотографию актера в хранилище
     *
     * @param UploadedFile $photo Файл фотографии
     * @return string Путь к сохраненному файлу
     */
    protected function uploadPhoto(UploadedFile $photo): string
    {
        return $photo->store('actors/photos', 'public');
    }

    /**
     * Удаляет старую фотографию актера
     *
     * @param string|null $photoPath Путь к фотографии
     * @return bool Успешность удаления
     */
    protected function deleteOldPhoto(?string $photoPath): bool
    {
        if ($photoPath && Storage::disk('public')->exists($photoPath)) {
            return Storage::disk('public')->delete($photoPath);
        }

        return false;
    }

    /**
     * Получает путь к фотографии актера или возвращает дефолтное изображение
     *
     * @param Actor $actor Актер
     * @return string URL фотографии
     */
    public function getActorPhotoUrl(Actor $actor): string
    {
        if ($actor->photo && Storage::disk('public')->exists($actor->photo)) {
            return Storage::url($actor->photo);
        }

        // Возвращаем URL дефолтного изображения актера
        return asset('images/default-actor.jpg');
    }
}
