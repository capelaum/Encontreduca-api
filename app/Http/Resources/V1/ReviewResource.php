<?php

namespace App\Http\Resources\V1;

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
            'resourceId' => $this->resource_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'updatedAt' => $this->updated_at,
            'user' => new UserResource($this->user),
        ];
    }
}
