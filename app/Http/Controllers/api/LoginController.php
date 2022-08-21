<?php

namespace App\Http\Controllers\api;

use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Exception;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;
use stdClass;

class LoginController extends Controller
{

    public function login(Request $request)
    {
       $email = $request->email;
       $password = $request->password;
       if($email && $password)
       {
            $user = User::where('email',$email)->first();
            if ($user && Hash::check($password, $user->password))
            {
                $data = $user->toArray();
                $data['roles'] = $user->role->only('name')['name'];
                $token = JWT::encode($data, $this->api_key, $this->hash);
                $payload = new stdClass();
                $payload->token = $token;
                $payload->data = $data;
                return response()->json($payload);
            }
       }
       return response()->json('Your credentials did not match',422);
    }

    public function verifyAuthen(Request $request)
    {
       $token = $request->access_token;

       try
       {
           $payload = JWT::decode($token,new Key($this->api_key,'HS256'));
           return response()->json($payload);
       }
       catch(Exception $ex)
       {
            return response()->json('Invalid Token',401);
       }

    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'password-confirm' =>[ 'required_with:password','same:password','min:6']]
        );

        $avatar = "images/avatar-default.svg";
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'avatar' => $avatar,
            'password' => Hash::make($data['password']),
            'created_at' => now()
        ]);

        return success('Created Successful');

    }
}
