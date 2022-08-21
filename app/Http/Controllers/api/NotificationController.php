<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __invoke()
    {
        $token = request()->header('access_token');
        $user = JWT::decode($token, new Key(config('api.secret_key'), config('api.hash')));
        $user = User::find($user->id);
        if($user)
        {
            $user->unreadNotifications->markAsRead();
            return success();
        }

        return error('Unauthorized', 401);

    }
}
