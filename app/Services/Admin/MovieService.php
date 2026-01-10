<?php

namespace App\Services\Admin;

use App\Models\Movie;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Сервис для работы с фильмами
 * Инкапсулирует бизнес-логику создания, обновления и удаления фильмов
 * Обрабатывает загрузку медиафайлов и связи с актерами/жанрами
 */
class MovieService
{
    /**
     * Поля для хранения файлов фильма
     */
    private const FILE_FIELDS = ['poster', 'banner', 'url', 'trailer_url'];

    /**
     * Пути для хранения файлов
     */
    private const STORAGE_PATHS = [
        'poster' => 'movies/posters',
        'banner' => 'movies/banners',
        'url' => 'movies/videos',
        'trailer_url' => 'movies/trailers',
    ];

    /**
     * Создает новый фильм с загрузкой файлов и привязкой связей
     *
     * @param array $data Валидированные данные фильма
     * @param array $fileData Массив загруженных файлов
     * @param array|null $genreIds ID жанров для привязки
     * @param array|null $actorIds ID актеров для привязки
     * @return Movie Созданный фильм
     * @throws \Exception В случае ошибки удаляет загруженные файлы
     */
    public function createMovie(
        array $data,
        array $fileData = [],
        ?array $genreIds = null,
        ?array $actorIds = null
    ): Movie {
        $uploadedFiles = [];

        try {
            return DB::transaction(function () use ($data, $fileData, $genreIds, $actorIds, &$uploadedFiles) {
                // Загружаем файлы если они были переданы
                foreach (self::FILE_FIELDS as $field) {
                    if (isset($fileData[$field]) && $fileData[$field] instanceof UploadedFile) {
                        $uploadedFiles[$field] = $this->uploadFile(
                            $fileData[$field],
                            self::STORAGE_PATHS[$field] ?? 'movies'
                        );
                        $data[$field] = $uploadedFiles[$field];
                    }
                }

                // Создаем фильм
                $movie = Movie::create($data);

                // Привязываем жанры если переданы
                if ($genreIds !== null) {
                    $this->syncGenres($movie, $genreIds);
                }

                // Привязываем актеров если переданы
                if ($actorIds !== null) {
                    $this->syncActors($movie, $actorIds);
                }

                return $movie->load(['genres', 'actors']);
            });
        } catch (\Exception $e) {
            // В случае ошибки удаляем загруженные файлы
            foreach ($uploadedFiles as $filePath) {
                if ($filePath) {
                    $this->deleteFile($filePath);
                }
            }

            throw $e;
        }
    }

    /**
     * Обновляет данные фильма
     *
     * @param Movie $movie Фильм для обновления
     * @param array $data Новые данные
     * @param array $fileData Новые файлы
     * @param array|null $genreIds ID жанров
     * @param array|null $actorIds ID актеров
     * @return Movie Обновленный фильм
     */
    public function updateMovie(
        Movie $movie,
        array $data,
        array $fileData = [],
        ?array $genreIds = null,
        ?array $actorIds = null
    ): Movie {
        return DB::transaction(function () use ($movie, $data, $fileData, $genreIds, $actorIds) {
            // Обновляем файлы если переданы новые
            foreach (self::FILE_FIELDS as $field) {
                if (isset($fileData[$field]) && $fileData[$field] instanceof UploadedFile) {
                    // Удаляем старый файл если он существует
                    if ($movie->$field) {
                        $this->deleteFile($movie->$field);
                    }

                    // Загружаем новый файл
                    $data[$field] = $this->uploadFile(
                        $fileData[$field],
                        self::STORAGE_PATHS[$field] ?? 'movies'
                    );
                }
            }

            // Обновляем данные фильма
            $movie->update($data);

            // Обновляем связи если переданы
            if ($genreIds !== null) {
                $this->syncGenres($movie, $genreIds);
            }

            if ($actorIds !== null) {
                $this->syncActors($movie, $actorIds);
            }

            return $movie->fresh(['genres', 'actors']);
        });
    }

    /**
     * Удаляет фильм и все связанные данные
     *
     * @param Movie $movie Фильм для удаления
     * @return bool Результат удаления
     */
    public function deleteMovie(Movie $movie): bool
    {
        return DB::transaction(function () use ($movie) {
            // Удаляем все файлы фильма
            foreach (self::FILE_FIELDS as $field) {
                if ($movie->$field) {
                    $this->deleteFile($movie->$field);
                }
            }

            // Отвязываем актеров и жанры
            $movie->actors()->detach();
            $movie->genres()->detach();

            // Удаляем фильм
            return $movie->delete();
        });
    }

    /**
     * Загружает файл в хранилище
     *
     * @param UploadedFile $file Загруженный файл
     * @param string $directory Директория для сохранения
     * @return string Путь к сохраненному файлу
     */
    protected function uploadFile(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }

    /**
     * Удаляет файл из хранилища
     *
     * @param string|null $filePath Путь к файлу
     * @return bool Успешность удаления
     */
    protected function deleteFile(?string $filePath): bool
    {

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return false;
    }

    /**
     * Привязывает жанры к фильму
     *
     * @param Movie $movie Фильм
     * @param array $genreIds ID жанров
     */
    protected function syncGenres(Movie $movie, array $genreIds): void
    {
        $movie->genres()->sync($genreIds);
    }

    /**
     * Привязывает актеров к фильму
     *
     * @param Movie $movie Фильм
     * @param array $actorIds ID актеров
     */
    protected function syncActors(Movie $movie, array $actorIds): void
    {
        $movie->actors()->sync($actorIds);
    }

    /**
     * Получает URL медиафайла фильма или возвращает дефолтное изображение
     *
     * @param Movie $movie Фильм
     * @param string $field Поле файла
     * @return string URL файла
     */
    public function getMovieMediaUrl(Movie $movie, string $field): string
    {
        // Проверяем что поле существует в списке файловых полей
        if (!in_array($field, self::FILE_FIELDS)) {
            throw new \InvalidArgumentException("Поле {$field} не является медиаполем фильма");
        }

        // Возвращаем URL если файл существует
        if ($movie->$field && Storage::disk('public')->exists($movie->$field)) {
            return Storage::url($movie->$field);
        }

        // Возвращаем дефолтное изображение в зависимости от типа
        return $this->getDefaultMediaUrl($field);
    }

    /**
     * Возвращает дефолтный URL для типа медиа
     *
     * @param string $field Тип медиа
     * @return string URL дефолтного изображения
     */
    protected function getDefaultMediaUrl(string $field): string
    {
        $defaults = [
            'poster' => asset('images/default-poster.jpg'),
            'banner' => asset('images/default-banner.jpg'),
            'url' => null, // Для видео нет дефолта
            'trailer_url' => null,
        ];

        return $defaults[$field] ?? asset('images/default-movie.jpg');
    }

    /**
     * Валидирует загружаемые файлы
     *
     * @param array $files Массив файлов
     * @param array $rules Правила валидации
     * @return array Валидированные файлы
     * @throws \Illuminate\Validation\ValidationException
     */

}
