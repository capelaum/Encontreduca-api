<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupportResource extends JsonResource
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
            'author' => $this->user->name,
            'authorEmail' => $this->user->email,
            'authorAvatar' => $this->user->avatar_url,
            'message' => $this->message,
            'createdAt' => date('d/m/Y H:i:s', strtotime($this->created_at)),
        ];
    }
}
