<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewFormRequest extends FormRequest
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
        $rules =  [
            "user_id" => "required|exists:users,id",
            "resource_id" => "required|exists:resources,id",
            "rating" => "required|numeric|between:1,5",
            "comment" => "required|string|max:1000"
        ];

        return $rules;
    }
}
