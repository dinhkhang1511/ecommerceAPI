<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\ImageServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

        if($request->limit == 'all')
            return CategoryResource::collection($query->get());

        $limit = request('limit', 10) ;
        return CategoryResource::collection($query->paginate($limit));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryStoreRequest $request)
    {
        Category::create(['name' => $request->name,
                          'image_path' => '']);
        return success('categories.index');
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
        if (request()->has('base64_image')) {
            delete_file($category->image_path);
        }
        $category->update(['name' => $request->name,
                           'image_path' => '']);
        return success('Updated successful',201);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        ImageServices::deleteImages($category);
        $category->delete();
        return success('Deleted successful',201);
    }
}
