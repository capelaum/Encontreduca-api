<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $method = $this->method();

        if ($method == 'PUT') {
            return [
                "name" => "required|string|min:3|max:255",
                "email" => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($this->user->id),
                ],
                "password" => "nullable|string|min:8|max:255",
                "avatarUrl" => "nullable|string|max:1000",
            ];
        }

        return [
            "name" => "sometimes|required|string|min:3|max:255",
            "email" => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user->id),
            ],
            "password" => "sometimes|nullable|string|min:8|max:255",
            "avatarUrl" => "sometimes|nullable|string|max:1000",
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->avatarUrl) {
            $this->merge([
                'avatar_url' => $this->avatarUrl,
            ]);
        }
    }
}
