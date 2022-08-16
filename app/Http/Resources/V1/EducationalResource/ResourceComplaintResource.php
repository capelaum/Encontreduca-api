<?php

namespace App\Http\Resources\V1\EducationalResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceComplaintResource extends JsonResource
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
            'resourceId' => $this->resource_id,
            'userId' => $this->user_id,
            'motiveId' => $this->motive_id,
            'createdAt' => date('d/m/Y', strtotime($this->created_at)),
        ];
    }
}
