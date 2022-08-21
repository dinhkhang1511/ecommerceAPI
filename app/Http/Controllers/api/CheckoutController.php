<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(CheckoutRequest $request)
    {

        $checkoutService = new CheckoutService($request->validated());
        //kiểm tra xem số lượng hàng tồn còn đủ so với số lượng mong muốn của user không
        if (!$checkoutService->checkQuantity(request()->all())) {
            return error('Your product is out of stock',400);
        }

        $checkoutService->store();
        return success();
    }
}
