<?php

namespace App\Http\Requests\Admin\Actor;

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
            'name'     => ['required', 'string', 'max:255'],
            'bio'      => ['nullable', 'string'],
            'birthday' => ['nullable', 'date'],
            'photo'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя актёра обязательно.',
            'photo.image'   => 'Фото должно быть изображением.',
            'photo.mimes'   => 'Фото может быть только jpg, jpeg, png или webp.',
            'birthday.date' => 'Некорректная дата рождения.',
        ];
    }
}
