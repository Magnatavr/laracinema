<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'email'=> 'required|string|email|max:50|unique:users,email',
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', 'in:user,admin'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле имени обязательно для заполнения.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не должно превышать 50 символов.',
            'password.required' => 'Пароль обязателен.',
            'password.min' => 'Пароль должен быть больше 6 символов',
            'email.required' => 'Поле email обязательно для заполнения.',
            'email.string' => 'Email должен быть строкой.',
            'email.email' => 'Введите корректный адрес электронной почты.',
            'email.max' => 'Email не должен превышать 50 символов.',
            'email.unique' => 'Такой email уже зарегистрирован.',


        ];
    }
}
