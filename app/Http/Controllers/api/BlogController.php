<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogStoreRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = Blog::query();
        $query->latest();
        $limit = request('limit',5);

        return BlogResource::collection($query->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogStoreRequest $request)
    {
        $blog = Blog::create($request->validated());
        $blog->tags()->attach($request->tags);
        return success($blog);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new BlogResource(Blog::find($id));
    }

    public function related($id)
    {
        $blog = Blog::find($id);
        return  BlogResource::collection(Blog::relatedPost($blog));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogUpdateRequest $request, $id)
    {
        $blog = Blog::find($id);
        if (request()->has('image_path')) {
            delete_file($blog->image_path);
        }

        $blog->update($request->validated());
        $blog->tags()->sync($request->tags);
        return success('blogs.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);
        delete_file($blog->image_path);
        $blog = $blog->delete();
        return success($blog);
    }
}
