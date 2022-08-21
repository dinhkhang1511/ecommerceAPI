<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->wrap('orders');
        return [
            'id' => $this->id,
            'order_no' => $this->order_no,
            'user_id' => $this->user_id,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'customer_email' => $this->customer_email,
            'customer_address' => $this->customer_address,
            'province' => ['id' => $this->province_id,
                           'name' => $this->province->name],
            'district' => ['id' => $this->district_id,
                           'name' => $this->district->name],
            'ward'     => ['id' => $this->ward_id,
                           'name' => $this->ward->name],
            'notes' => $this->notes,
            'price' => $this->price,
            'discount' => $this->discount,
            'status' => $this->status,
            'status_color' => $this->status_color,
            'details' => OrderDetailResource::collection($this->whenLoaded('details')),
            'created_at' => $this->created_at->format('d F, Y'),
            'updated_at' => $this->updated_at->format('d F, Y')
        ];
    }
}
