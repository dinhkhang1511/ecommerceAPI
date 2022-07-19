<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
