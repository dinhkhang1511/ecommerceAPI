<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getProvinces()
    {
        return LocationResource::collection(Province::all());
    }

    public function getDistricts()
    {
        return LocationResource::collection(District::all());
    }

    public function getWards()
    {
        return LocationResource::collection(Ward::all());
    }


}
