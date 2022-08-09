<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceFormRequest extends FormRequest
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
            "user_id" => "required|exists:users,id",
            "category_id" => "required|exists:categories,id",
            "name" => "required|string|min:3|max:255",
            "latitude" => "required|numeric|between:-90,90",
            "longitude" => "required|numeric|between:-180,180",
            "address" => "required|string|min:3|max:255",
            "website" => "nullable|string|min:7|max:255",
            "phone" => "nullable|string|min:14|max:15",
            "cover" => "required|string|max:1000",
            "approved" => "nullable|boolean"
        ];

        return $rules;
    }
}
