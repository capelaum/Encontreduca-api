<?php

namespace App\Http\Requests\V1\EducationalResource;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResourceVoteRequest extends FormRequest
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
                "userId" => "required|integer|exists:users,id",
                "resourceId" => "required|integer|exists:resources,id",
                "vote" => "required|boolean",
                "justification" => "required|string|min:3"
            ];
        }

        return [
            "userId" => "sometimes|required|integer|exists:users,id",
            "resourceId" => "sometimes|required|integer|exists:resources,id",
            "vote" => "sometimes|required|boolean",
            "justification" => "sometimes|required|string|min:3"
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->userId,
            'resource_id' => $this->resourceId,
        ]);
    }
}
