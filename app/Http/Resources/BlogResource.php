<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->wrap('blogs');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => substr(strip_tags($this->body),0,50),
            'image_path' => $this->image_path,
            'tags'       =>$this->tags,
            'created_at' => $this->created_at->format('d F, Y'),
            'updated_at' => $this->updated_at->format('d F, Y')
        ];
    }
}
