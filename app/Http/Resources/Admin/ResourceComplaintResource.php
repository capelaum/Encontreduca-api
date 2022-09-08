<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'resourceId' => $this->resource_id,
            'resourceName' => $this->resource->name,
            'motiveId' => $this->motive_id,
            'createdAt' => date('d/m/Y', strtotime($this->created_at)),
        ];
    }
}
