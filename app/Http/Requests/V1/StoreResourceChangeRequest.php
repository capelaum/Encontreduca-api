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
            "userId" => "required|exists:users,id",
            "resourceId" => "required|exists:resources,id",
            "field" => "required|string|max:255|in:{$fieldList}",
            "oldValue" => "required|string|max:255",
            "newValue" => "required|string|max:255",
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->userId,
            'resource_id' => $this->resourceId,
            'old_value' => $this->oldValue,
            'new_value' => $this->newValue,
        ]);
    }
}
