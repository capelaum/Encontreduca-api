<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
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
            'name' => $this->name,
            'address' => $this->address,
            'position' => [
                'lat' => (float) $this->latitude,
                'lng' => (float) $this->longitude
            ],
            'website' => $this->website,
            'phone' => $this->phone,
            'cover' => $this->cover,
            'approved' => $this->approved,
            'createdAt' => date('d/m/Y', strtotime($this->created_at)),
            'updatedAt' => date('d/m/Y', strtotime($this->updated_at)),
            'userId' => $this->user_id,
            'user' => new UserResource($this->user),
            'categoryId' => $this->category_id,
            'category' => new CategoryResource($this->category),
            'votes' => new VoteCollection($this->votes),
            'reviews' => new ReviewCollection($this->reviews),
        ];
    }
}
