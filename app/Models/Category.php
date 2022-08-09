<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    // Each category may have one parent
    public function parent() {
        return $this->belongsTo(static::class, 'category_id');
    }

    // Each category may have multiple children
    public function children() {
        return $this->hasMany(static::class, 'category_id');
    }

    public function products()
    {
        $subcategories = Category::whereIn('category', request('category'))->modelKeys();
        return Product::with('images')->whereIn('category_id', $subcategories)->get();
    }

    public function setImagePathAttribute()
    {
        if (request()->has('image_path')) {
            $path = store_file(request('image_path'), 'category');
            $this->attributes['image_path'] = $path;
        }
    }
}
