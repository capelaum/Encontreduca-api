<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|min:3|max:255",
            "email" => "required|string|email|max:255|unique:users",
            "password" => "required|string|min:8|max:255|confirmed",
            "confirmPassword" => "required|string|min:8|max:255",
            "avatar" => "nullable|file|mimes:jpg,png,svg,webp",
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'password_confirmation' => $this->confirmPassword
        ]);
    }
}
