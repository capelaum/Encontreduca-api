<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreResourceComplaintRequest extends FormRequest
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
            "motive_id" => "required|exists:motives,id"
        ];

        return $rules;
    }
}
