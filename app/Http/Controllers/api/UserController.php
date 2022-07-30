<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
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
        return UserResource::collection(User::all());
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
    public function destroy($id)
    {
        //
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
                if(isset($fields) && !empty($fields[0]))
                    $user = $user->load($fields);

                return response()->json($user);

            }
            catch(Exception $ex)
            {
                return response($ex->getMessage(),401);
            }
        }
        return response('Invalid Token',401);

    }
}
