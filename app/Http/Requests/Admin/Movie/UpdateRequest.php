<?php

namespace App\Http\Requests\Admin\Movie;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'year'         => ['nullable', 'integer', 'between:1900,' . date('Y')],
            'rating'       => ['nullable', 'numeric', 'between:0,10'],
            'age_rating'   => ['nullable', 'string', 'in:G,PG,PG-13,R,NC-17'],
            'status'       => ['required', 'in:draft,published'],

            // изображения
            'poster'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
            'banner'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],

            // видео
            'url'          => ['nullable', 'file', 'mimetypes:video/mp4,video/webm'],
            'trailer_url'  => ['nullable', 'file', 'mimetypes:video/mp4,video/webm'],

            // duration
            'duration'     => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Название фильма обязательно.',
            'title.max'            => 'Название не может превышать 255 символов.',

            'description.string'   => 'Описание должно быть текстом.',

            'year.integer'         => 'Год должен быть числом.',
            'year.between'         => 'Год должен быть от 1900 до ' . date('Y') . '.',

            'rating.numeric'       => 'Рейтинг должен быть числом.',
            'rating.between'       => 'Рейтинг должен быть от 0 до 10.',

            'age_rating.string'    => 'Возрастной рейтинг должен быть строкой.',
            'age_rating.max'       => 'Возрастной рейтинг не должен превышать 10 символов.',
            'age_rating.in'     => 'Выберите корректный возрастной рейтинг.',

            'status.required'      => 'Статус обязателен.',
            'status.in'            => 'Выберите корректный статус (draft или published).',

            'poster.image'         => 'Постер должен быть изображением.',
            'poster.mimes'         => 'Постер может быть только jpg, jpeg, png или webp.',

            'banner.image'         => 'Баннер должен быть изображением.',
            'banner.mimes'         => 'Баннер может быть только jpg, jpeg, png или webp.',

            'url.file'           => 'Видео должно быть файлом.',
            'url.mimetypes'      => 'Видео может быть только MP4 или WEBM.',

            'trailer_url.file'         => 'Трейлер должен быть файлом.',
            'trailer_url.mimetypes'    => 'Трейлер может быть только MP4 или WEBM.',
        ];
    }
}
