<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
                "user_id" => "required|exists:users,id",
                "resource_id" => "required|exists:resources,id",
                "rating" => "required|numeric|between:1,5",
                "comment" => "required|string|min:3|max:1000"
            ];
        }

        return [
            "user_id" => "required|exists:users,id",
            "resource_id" => "required|exists:resources,id",
            "rating" => "sometimes|required|numeric|between:1,5",
            "comment" => "sometimes|required|string|min:3|max:1000"
        ];
    }
}
