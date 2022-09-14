<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'avatarUrl' => $this->avatar_url,
            'reviewsCount' => $this->reviews()->count(),
            'resourcesCount' => $this->resources()->count(),
            'savedResourcesCount' => $this->savedResources()->count(),
            'votesCount' => $this->votes()->count(),
        ];
    }
}
