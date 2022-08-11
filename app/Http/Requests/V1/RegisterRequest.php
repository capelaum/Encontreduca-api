<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            "name" => "required|string|min:3|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:6|max:255|confirmed",
            "confirmPassword" => "required|string|min:6|max:255",
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'password_confirmation' => $this->confirmPassword
        ]);
    }
}
