<?php

namespace App\Http\Requests\V1;

use App\Models\ResourceChange;
use Illuminate\Foundation\Http\FormRequest;

class StoreResourceChangeRequest extends FormRequest
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
        $fieldList = implode(',', ResourceChange::$fields);

        return [
            "user_id" => "required|exists:users,id",
            "resource_id" => "required|exists:resources,id",
            "field" => "required|string|max:255|in:{$fieldList}",
            "old_value" => "required|string|max:255",
            "new_value" => "required|string|max:255",
        ];
    }
}
