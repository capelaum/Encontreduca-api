<?php

namespace App\Http\Resources\V1\Review;

use App\Http\Resources\V1\MotiveResource;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'reviewId' => $this->review_id,
            'motiveId' => $this->motive_id,
        ];
    }
}
