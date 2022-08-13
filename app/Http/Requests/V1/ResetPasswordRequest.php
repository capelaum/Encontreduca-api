<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ResetPasswordRequest extends FormRequest
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
        return [
            "token" => "required|string",
            "email" => "required|string|email|max:255",
            "password" => "required|string|min:8|max:255|confirmed",
            "confirmPassword" => "required|string|min:8|max:255",
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'password_confirmation' => $this->confirmPassword
        ]);
    }
}
