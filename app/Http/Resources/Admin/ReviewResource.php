<?php

namespace App\Http\Resources\Admin;

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
            'authorEmail' => $this->user->email,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'updatedAt' => date('d/m/Y', strtotime($this->updated_at)),
        ];
    }
}
