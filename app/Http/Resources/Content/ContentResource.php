<?php

namespace App\Http\Resources\Content;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'link_thumbnail' => $this->link_thumbnail,
            'link_video' => $this->link_video,
            'status' => $this->status,
            'view' => $this->view,
            'store_at' => $this->created_at->diffForHumans(),
        ];
    }
}
