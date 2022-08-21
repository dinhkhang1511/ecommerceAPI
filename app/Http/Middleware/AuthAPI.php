<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class AuthAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->isMethod('GET'))
            return $next($request);

        if($request->hasHeader('access_token') )
        {
            try
            {
                $token = $request->header('access_token');
                $payload = JWT::decode($token,new Key(config('api.secret_key'),config('api.hash')));
                $user = User::find($payload->id);
                if($user && $user->isAdmin())
                    return $next($request);
            } catch(Exception $ex)
            {
                Log::info($ex->getMessage());
            }
        }
        return error('Invalid Token',401);
    }
}
