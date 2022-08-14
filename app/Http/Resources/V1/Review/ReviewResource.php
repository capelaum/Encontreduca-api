<?php

namespace App\Http\Resources\V1\Review;

use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'resourceId' => $this->resource_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'updatedAt' => date('d/m/Y', strtotime($this->updated_at)),
        ];
    }
}
