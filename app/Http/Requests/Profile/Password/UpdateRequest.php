<?php

namespace App\Http\Requests\Profile\Password;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Русские сообщения об ошибках
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Введите текущий пароль',
            'current_password.string'   => 'Текущий пароль должен быть строкой',

            'password.required'         => 'Введите новый пароль',
            'password.string'           => 'Новый пароль должен быть строкой',
            'password.min'              => 'Новый пароль должен быть не менее :min символов',
            'password.confirmed'        => 'Пароли не совпадают',
        ];
    }

    /**
     * Человеческие названия полей (опционально, но красиво)
     */
    public function attributes(): array
    {
        return [
            'current_password' => 'текущий пароль',
            'password'         => 'новый пароль',
        ];
    }
}
