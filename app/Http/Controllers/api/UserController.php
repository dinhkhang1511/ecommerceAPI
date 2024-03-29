<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Resources\UserResource;
use App\Mail\ResetPasswordMail;
use App\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use stdClass;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $limit = request('limit',10);
        if( $limit == 'all')
            return UserResource::collection(User::latest()->get());
        else
            return UserResource::collection(User::latest()->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id === 'find')
        {
            $arrId =request()->id;
            return UserResource::collection(User::find($arrId));
        }
        return error('Not found',404);
    }

    public function getUserWishlist($id)
    {
        if(intval($id))
        {
                $wishlist = User::find($id)->wishlist->load('product');
                $payload = new stdClass();
                $payload->wishlist = $wishlist;
                return response()->json($payload);
        }
        return error('Not found',404);
    }

    public function getUserByMonth()
    {
        $month = request('m', date('m'));
        $users = UserResource::collection(User::whereMonth('created_at', $month)
        ->whereYear('created_at', date('Y'))
        ->customer()
        ->latest()
        ->get());
        return $users;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return success();
    }

    public function getDetails(Request $request)
    {
        if($request->hasHeader('access_token'))
        {
            try
            {
                if($request->has('fields'))
                    $fields = explode(',',$request->fields);

                $token = $request->header('access_token');
                $payload = JWT::decode($token,new Key($this->api_key,$this->hash));
                $user = User::find($payload->id);
                if($user->role_id == 1 && $request->has('user_id'))
                {
                    $user = User::find($request->user_id);
                }
                if(isset($fields) && !empty($fields[0]) && $user)
                    $user = $user->load($fields);

                return response()->json($user);

            }
            catch(Exception $ex)
            {
                Log::info($ex->getMessage());
                return error('Invalid Token',401);
            }
        }
        return error('Invalid Token',401);

    }

    // public function checkPassword(Request $request)
    // {
    //     $token = $request->header('access_token');
    //     dd($token);
    //     $user = JWT::decode($token,new Key(config('api.secret_key'),config('api.hash')));
    //     $user = User::find($user->id);
    //     if($user)
    //     {
    //         $user->makeVisible('password');

    //         return Hash::check($request->password, $user->password) ? success('Password valid') : error('Password is invalid', 400);
    //     }

    //     return error('User not found', 404);
    // }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        $token = $request->header('access_token');
        $user = JWT::decode($token,new Key(config('api.secret_key'),config('api.hash')));
        $user = User::find($user->id);
        if($user && $request->has('new_password'))
        {
            $user->update(['password' => Hash::make($request->new_password)]);
            return success();
        }

        return error('User not found', 404);
    }

    public function forgetPassword(Request $request)
    {
        $email = $request->email;
        if($email && $user = User::where('email', $email)->first())
        {
            $token = Str::random(32);

            $user->forget_password_token = $token;
            $user->forget_password_token_expired = date('Y-m-d h:i:s',strtotime('+10 minutes'));
            $user->save();
            Mail::to($user->email)->queue(new ResetPasswordMail($token,$user));
            return success();
        }
        return error('Email is not exist', 404);

    }

    public function checkToken(Request $request) // * Forget password
    {
        $data = explode('.', $request->token);
        $token = $data[0];
        $user_id = $data[1];

        if($token && $user = User::find($user_id))
        {

            if(! ($token === $user->forget_password_token))
            {
                return error('Token invalid', 402);
            }
            else
            {
                if(now() > $user->forget_password_token_expired)
                {
                    return error('Token expired', 402);
                }
                else
                    return success();
            }
        }

        return error('Token invalid', 402);
    }

    public function resetPassword(Request $request) // * reset password
    {
        $data = explode('.', $request->token);
        $token = $data[0];
        $user_id = $data[1];

        if($token && $user = User::find($user_id))
        {
            if(! $token === $user->forget_password_token)
            {
                return error('Token invalid', 402);
            }
            else
            {
                if(now() > $user->forget_password_token_expired)
                {
                    return error('Token expired', 402);
                }
                else
                {
                    if($request->password == $request->password_confirmation)
                    {
                        $user->password = Hash::make($request->password);
                        $user->save();
                        return success();
                    }
                }
            }
        }

        return error('Token invalid', 402);
    }

    public function setAdmin(Request $request, $id)
    {
        $user = User::find($id);
        if($user->role_id != 1)
            $user->update(['role_id' => 1]);

        return success();
    }
}
