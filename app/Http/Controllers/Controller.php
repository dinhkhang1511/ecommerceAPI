<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use stdClass;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $api_key;
    protected $hash;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->api_key =config('api.secret_key');
        $this->hash =config('api.hash','HS256');
    }
}
