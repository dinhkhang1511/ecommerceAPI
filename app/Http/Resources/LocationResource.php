<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->when($this->code, $this->code),
            'prefix' => $this->when($this->prefix, $this->prefix),
            'province_id' => $this->when($this->province_id, $this->province_id),
            'district_id' => $this->when($this->district_id, $this->district_id),
        ];
    }
}
