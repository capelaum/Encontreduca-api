<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewComplaintRequest extends FormRequest
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
            'userId' => 'required|integer|exists:users,id',
            'reviewId' => 'required|integer|exists:reviews,id',
            'motiveId' => 'required|integer|exists:motives,id',
        ];

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->userId,
            'review_id' => $this->reviewId,
            'motive_id' => $this->motiveId,
        ]);
    }
}
