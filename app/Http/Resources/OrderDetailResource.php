<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->wrap('details');
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product' => Product::find($this->product_id)->only('name'),
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'total' => $this->total,
            'size' => ['id' => $this->size_id,
                       'name' => $this->size->name],
            'color' => ['id' => $this->color_id,
                        'name' => $this->color->name],
            'created_at' => $this->created_at->format('d F, Y'),
            'updated_at' => $this->updated_at->format('d F, Y')
        ];
    }
}
