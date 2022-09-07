<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResourceRequest extends FormRequest
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
    public function rules()
    {
        $method = $this->method();

        if ($method === 'PUT') {
            return [
                "categoryId" => "required|exists:categories,id",
                "name" => "required|string|min:3|max:255",
                "latitude" => "required|numeric|between:-90,90",
                "longitude" => "required|numeric|between:-180,180",
                "address" => "required|string|min:3|max:255",
                "website" => "nullable|string|min:7|max:255",
                "phone" => "nullable|string|min:14|max:15",
                "cover" => "required|file|mimes:jpg,png,svg,webp",
                "approved" => "required|boolean",
            ];
        }

        return [
            "categoryId" => "sometimes|required|exists:categories,id",
            "name" => "sometimes|required|string|min:3|max:255",
            "latitude" => "sometimes|required|numeric|between:-90,90",
            "longitude" => "sometimes|required|numeric|between:-180,180",
            "address" => "sometimes|required|string|min:3|max:255",
            "website" => "sometimes|nullable|string|min:7|max:255",
            "phone" => "sometimes|nullable|string|min:14|max:15",
            "cover" => "sometimes|required|file|mimes:jpg,png,svg,webp",
            "approved" => "sometimes|required|boolean",
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->categoryId) {
            $this->merge([
                'category_id' => $this->categoryId,
            ]);
        }
    }
}
