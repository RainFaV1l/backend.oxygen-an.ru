<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeRequest extends FormRequest
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
            'email' => 'required|string|email|max:255|min:5|unique:subscribes,email'
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
            'email.unique' => 'Данный email уже подписан на рассылку',
            'email.max' => 'Максимальное количество символов в поле email равна 255',
            'email.min' => 'Минимальное количество символов в поле email равна 5',
        ];
    }
}
