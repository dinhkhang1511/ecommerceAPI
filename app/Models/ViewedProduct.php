<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewedProduct extends Model
{
    protected $table = 'viewed_products';
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }

    public static function view($product)
    {
        if ($user_id = request()->header('user_id'))
            ViewedProduct::updateOrCreate(['user_id' =>  $user_id, 'product_id' => $product->id])->increment('view_numbers');
    }
}
