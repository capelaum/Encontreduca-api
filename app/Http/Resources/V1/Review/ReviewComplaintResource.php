<?php

namespace App\Http\Resources\V1\Review;

use App\Http\Resources\V1\MotiveResource;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'reviewId' => $this->review_id,
            'motiveId' => $this->motive_id,
        ];
    }
}
