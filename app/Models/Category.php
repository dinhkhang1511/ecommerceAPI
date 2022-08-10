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
        if (request()->has('base64_image')) {
            $name = time() . '.' . request()->extension;
            $path = 'uploads/category/' . $name;
            $imageBase64 = request()->base64_image;
            file_put_contents(public_path($path), base64_decode($imageBase64));
            $this->attributes['image_path'] = $path;
        }
    }
}
