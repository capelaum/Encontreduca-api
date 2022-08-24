<?php

namespace App\Http\Resources\V1\EducationalResource;

use App\Http\Resources\V1\CategoryResource;
use App\Http\Resources\V1\Review\ReviewCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
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
            'author' => $this->user ? $this->user->name : 'Anônimo',
            'categoryId' => $this->category_id,
            'categoryName' => $this->category->name,
            'name' => $this->name,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'website' => $this->website,
            'phone' => $this->phone,
            'cover' => $this->cover,
            'approved' => $this->approved,
            'createdAt' => date('d/m/Y', strtotime($this->created_at)),
            'updatedAt' => date('d/m/Y', strtotime($this->updated_at))
        ];
    }
}
