<?php

namespace App\Http\Controllers\Admin\Movie;

use App\Http\Controllers\Controller;
use App\Services\Admin\MovieService;

class BaseController extends Controller
{
    /**
     * Сервис для работы с фильмами
     *
     * @var MovieService
     */
    protected $movieService;

    /**
     * Конструктор контроллера
     *
     * @param MovieService $movieService Сервис фильмов
     */
    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }


    /**
     * Извлекает файлы из запроса
     *
     * @return array Массив файлов
     */
    protected function extractFilesFromRequest($request): array
    {
        $files = [];
        $fileFields = ['poster', 'banner', 'url', 'trailer_url'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $files[$field] = $request->file($field);
            }
        }

        return $files;
    }

}
