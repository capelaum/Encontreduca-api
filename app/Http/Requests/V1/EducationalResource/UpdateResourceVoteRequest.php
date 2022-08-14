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
                "vote" => "required|boolean",
                "justification" => "required|string|min:3"
            ];
        }

        return [
            "vote" => "sometimes|required|boolean",
            "justification" => "sometimes|required|string|min:3"
        ];
    }
}
