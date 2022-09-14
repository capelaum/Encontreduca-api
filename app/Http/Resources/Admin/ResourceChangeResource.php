<?php

namespace App\Http\Resources\Admin;

use App\Models\Resource;
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
            'author' => $this->user ? $this->user->name : 'Anônimo',
            'authorEmail' => $this->user ? $this->user->email : 'Anônimo',
            'authorAvatar' => $this->user ? $this->user->avatar_url : null,
            'resourceId' => $this->resource_id,
            'resourceName' => Resource::find($this->resource_id)->name,
            'field' => $this->field,
            'oldValue' => $this->old_value,
            'newValue' => $this->new_value,
            'createdAt' => date('d/m/Y', strtotime($this->created_at)),
        ];
    }
}
