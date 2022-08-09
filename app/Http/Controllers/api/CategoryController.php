<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\ImageServices;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Category::query();
        if($request->has('parent'))
        {
            if($request->parent == 1)
                $query->where('category_id',NULL);
            else if($request->parent == 0)
                $query->where('category_id',"!=", NULL);
        }
        if($request->has('limit'))
        {
           $limit = intval($request->limit);
        }
        else
        {
            $limit = 10;
        }
        return CategoryResource::collection($query->paginate($limit));
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
        return  new CategoryResource(Category::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        $category = Category::find($id);
        if (request()->has('image_path')) {
            delete_file($category->image_path);
        }
        $category->update($request->validated());
        return success('Updated successful',201);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // ImageServices::deleteImages($category);
        // $category->delete();
        // return success('categories.index');
    }
}
