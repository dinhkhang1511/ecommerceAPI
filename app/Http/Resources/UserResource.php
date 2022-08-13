<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->wrap('user');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'date_of_birth' =>$this->date_of_birth,
            'address' =>$this->address,
            'province_id' =>$this->province_id,
            'district_id' =>$this->district_id,
            'ward_id' =>$this->ward_id,
            'phone' =>$this->phone,
            'avatar' =>$this->avatar,
            'created_at' => $this->created_at ? $this->created_at->format('d F, Y') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d F, Y') : null
        ];
    }
}
