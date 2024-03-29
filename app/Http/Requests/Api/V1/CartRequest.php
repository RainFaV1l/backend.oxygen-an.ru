<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
            'email' => 'required|email|string|max:255',
            'password' => 'required|max:255|string'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Поле email является обязательным',
            'email.email' => 'Введите корректный email',
            'email.string' => 'Поле email должно быть строкой',
            'email.max' => 'Максимальное количество символов в поле email равна 255',
            'password.required' => 'Поле пароль является обязательным',
            'password.max' => 'Максимальное количество символов в поле пароль равна 255',
            'password.string' => 'Поле пароль должно быть строкой',
        ];
    }
}
