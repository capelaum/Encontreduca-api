<?php

namespace App\Http\Resources\V1\EducationalResource;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceChangeResource extends JsonResource
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
            'field' => $this->field,
            'oldValue' => $this->old_value,
            'newValue' => $this->new_value,
            'createdAt' => date('d/m/Y', strtotime($this->created_at)),
            'userId' => $this->user_id,
            'resourceId' => $this->resource_id,
        ];
    }
}
