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
            "name" => "required|string|max:255",
            "latitude" => "required|numeric|between:-90,90",
            "longitude" => "required|numeric|between:-180,180",
            "address" => "required|string|max:255",
            "website" => "nullable|string|max:255",
            "phone" => "nullable|string|max:255",
            "cover" => "nullable|string|max:1000",
            "approved" => "required|boolean"
        ];

        return $rules;
    }
}
