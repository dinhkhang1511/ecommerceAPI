<?php

use App\Services\CartService;
use Illuminate\Support\Facades\File;
if (! function_exists('error')) {
    function success($data = []  , $status = 200, $message = 'Operation Success')
    {
        $payload = new stdClass();
        $payload->status = $status;
        $payload->message = $message;
        $payload->data = $data;
        return response()->json($payload,$status);
    }
}

if (! function_exists('error')) {
    function error($errors = [], $status = 500, $message = 'Operation Fail')
    {
        $payload = new stdClass();
        $payload->status = $status;
        $payload->message = $message;
        $payload->errors = $errors;
        return response()->json($payload, $status);
    }
}

if (! function_exists('delete_file')) {
    function delete_file($path)
    {
        if ($path != 'images/avatar-default.svg') {
            File::delete($path);
        }
    }
}

if (! function_exists('store_file')) {
    function store_file($image, $folder)
    {
        return $image->store("uploads/$folder", 'public');
    }
}

if (! function_exists('money')) {
    function money($money)
    {
        return '$' . number_format($money, 2, '.', '.');
    }
}

if (! function_exists('cart')) {
    function cart()
    {
        return new CartService();
    }
}
