<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'modified_id' => $this->modifie_id,
            'name' => $this->name,
            'about' => $this->about,
            'avatar' => $this->avatar,
            'author_id' => $this->author_id,
            'created_at' => $this->created_at,
            'last_message' => $this->whenLoaded('last_message')
        ];
    }
}
