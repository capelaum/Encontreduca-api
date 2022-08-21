<?php

namespace App\Http\Resources\V1\Review;

use App\Http\Resources\V1\UserResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'resourceId' => $this->resource_id,
            'author' => $this->user->name,
            'authorAvatar' => $this->user->avatar_url,
            'authorReviewCount' => $this->user->reviews->count(),
            'rating' => $this->rating,
            'comment' => $this->comment,
            'updatedAt' => date('d/m/Y', strtotime($this->updated_at)),
        ];
    }
}
