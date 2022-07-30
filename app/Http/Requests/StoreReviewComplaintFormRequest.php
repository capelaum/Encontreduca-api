<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewComplaintFormRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:users,id',
            'review_id' => 'required|integer|exists:reviews,id',
            'motive_id' => 'required|integer|exists:motives,id',
        ];

        return $rules;
    }
}
