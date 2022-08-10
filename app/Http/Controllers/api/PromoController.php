<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromoRequest;
use App\Http\Resources\PromoResource;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->has('limit'))
            $limit = request()->limit;
        else
            $limit = 10;
        return PromoResource::collection(Promo::paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PromoRequest $request)
    {
        $promo = Promo::create($request->all());
        return success($promo);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Promo $promo)
    {
        return new PromoResource($promo);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PromoRequest $request, Promo $promo)
    {
        $data = $request->all();

        $promo->update($data);
        return success('Update Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promo $promo)
    {
        $promo = $promo->delete();
        return success('Deleted Successfully');
    }
}
