<?php

namespace App\Http\Resources\V1\EducationalResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceChangeResource extends JsonResource
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
            'field' => $this->field,
            'oldValue' => $this->old_value,
            'newValue' => $this->new_value,
            'createdAt' => date('d/m/Y', strtotime($this->created_at)),
        ];
    }
}
