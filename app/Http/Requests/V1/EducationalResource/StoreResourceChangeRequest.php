<?php

namespace App\Http\Requests\V1\EducationalResource;

use App\Models\ResourceChange;
use Illuminate\Foundation\Http\FormRequest;

class StoreResourceChangeRequest extends FormRequest
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
        $fieldList = implode(',', ResourceChange::$fields);

        return [
            "resourceId" => "required|exists:resources,id",
            "field" => "required|string|max:255|in:$fieldList",
            "oldValue" => "required|string|max:255",
            "newValue" => "required|string|max:255",
            "cover" => "nullable|file|mimes:jpg,png,svg,webp"
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'resource_id' => $this->resourceId,
            'old_value' => $this->oldValue,
            'new_value' => $this->newValue,
        ]);
    }
}
