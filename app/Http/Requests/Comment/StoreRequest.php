<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Пользователь должен быть авторизован
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'movie_id' => ['required', 'integer', 'exists:movies,id'],
            'rating'   => ['required', 'integer', 'min:1', 'max:10'],
            'comment'  => ['required', 'string', 'min:1', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'movie_id.required' => 'Фильм не указан.',
            'movie_id.exists'   => 'Указанный фильм не найден.',

            'rating.required' => 'Пожалуйста, поставьте оценку.',
            'rating.integer'  => 'Оценка должна быть числом.',
            'rating.min'      => 'Минимальная оценка — 1.',
            'rating.max'      => 'Максимальная оценка — 10.',

            'comment.required' => 'Комментарий обязателен.',
            'comment.min'      => 'Комментарий должен содержать минимум :min символов.',
            'comment.max'      => 'Комментарий не может превышать :max символов.',
        ];
    }
}
