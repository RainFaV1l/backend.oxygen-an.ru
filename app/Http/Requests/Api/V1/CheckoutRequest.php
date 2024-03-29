<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'user_id' => 'nullable|int|exists:users,id',
            'status_id' => 'nullable|int|exists:cart_statuses,id',
            'total' => 'required|numeric|max:1000000',
            'full_name' => 'required|string|max:255',
            'telephone' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'height' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'promotional_code' => 'nullable|string|max:255',
            'products' => 'required|array',
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
            'email.unique' => 'Email зарегистрирован на сайте. Пожалуйста, войдите в аккаунт',
            'email.max' => 'Максимальное количество символов в поле email равна 255',
            'full_name.required' => 'Поле ФИО является обязательным',
            'full_name.max' => 'Максимальное количество символов в поле ФИО равна 255',
            'full_name.string' => 'Поле ФИО должно быть строкой',
            'telephone.required' => 'Поле телефон является обязательным',
            'telephone.max' => 'Максимальное количество символов в поле телефон равна 255',
            'telephone.string' => 'Поле телефон должно быть строкой',
            'height.required' => 'Поле рост является обязательным',
            'height.max' => 'Максимальное количество символов в поле рост равна 255',
            'height.string' => 'Поле рост должно быть строкой',
            'city.required' => 'Поле город является обязательным',
            'city.max' => 'Максимальное количество символов в поле город равна 255',
            'city.string' => 'Поле город должно быть строкой',
            'promotional_code.max' => 'Максимальное количество символов в поле промокод равна 255',
            'promotional_code.string' => 'Поле промокод должно быть строкой',
        ];
    }
}
