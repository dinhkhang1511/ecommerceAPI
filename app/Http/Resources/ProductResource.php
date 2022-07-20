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
            'discount' => $this->discount,
            'description' => substr(strip_tags($this->description),0,50), // Convert html to plain text and substring
            'sub_category' => $this->subCategory,
            'rating' => $this->rating
        ];
    }
}
