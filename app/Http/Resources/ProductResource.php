<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->wrap('products');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'first_image' => $this->first_image,
            'discount' => $this->discount,
            'after_discount' => $this->after_discount,
            'description' => $this->when($request->routeIs('products.show'), $this->description, substr(strip_tags($this->description),0,50)), // Convert html to plain text and substring
            'category' => $this->category->name,
            'parent_category' => $this->parentCategory->name,
            'rating' => $this->rating,
            'orders' => $this->whenLoaded('orders'),
            'sizes' => SizeResource::collection($this->whenLoaded('sizes')),
            'colors' => ColorResource::collection($this->whenLoaded('colors')),
        ];
    }
}
