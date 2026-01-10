<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Авторизация запроса
     */
    public function authorize(): bool
    {
        // Так как это профиль авторизованного пользователя
        return auth()->check();
    }

    /**
     * Правила валидации
     */
    public function rules(): array
    {
        $user = $this->user(); // текущий авторизованный пользователь

        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'regex:/^\+7\d{10}$/',
            ],

            'birth_date' => [
                'nullable',
                'date',
                'before:today',
            ],

            'about' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'avatar' => [
                'nullable',
                'image',
            ],

            'email_notifications' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    /**
     * Подготовка данных перед валидацией
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email_notifications' => $this->boolean('email_notifications'),
        ]);
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя обязательно для заполнения.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не должно превышать 255 символов.',

            'email.required' => 'Email обязателен для заполнения.',
            'email.email' => 'Введите корректный email адрес.',
            'email.max' => 'Email не должен превышать 255 символов.',
            'email.unique' => 'Этот email уже используется.',

            'phone.regex' => 'Телефон должен быть в формате +79991234567.',

            'birth_date.date' => 'Дата рождения должна быть корректной датой.',
            'birth_date.before' => 'Дата рождения не может быть в будущем.',

            'about.max' => 'Описание не должно превышать 1000 символов.',

            'city.max' => 'Название города не должно превышать 100 символов.',

            'avatar.image' => 'Файл должен быть изображением.',
            'avatar.mimes' => 'Допустимые форматы: jpeg, png, jpg, gif.',
            'avatar.max' => 'Размер изображения не должен превышать 2 МБ.',

            'email_notifications.boolean' => 'Некорректное значение уведомлений.',
        ];
    }

}
