<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->wrap('settings');
        return [
            'id'            => $this->id,
            'shop_name'     => $this->shop_name,
            'site_title'    => $this->site_title,
            'favicon'       => $this->favicon,
            'logo'          => $this->logo,
            'email'         => $this->email,
            'copyright_text'=> $this->copyright_text,
            'phone'         => $this->phone,
            'address'       => $this->address,
            'province_id'   => $this->province_id,
            'district_id'   => $this->district_id,
            'ward_id'       => $this->ward_id,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at
        ];
    }
}
