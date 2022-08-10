<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceVoteResource extends JsonResource
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
            'vote' => $this->vote,
            'justification' => $this->justification,
            'updatedAt' => date('d/m/Y', strtotime($this->updated_at)),
        ];
    }
}
