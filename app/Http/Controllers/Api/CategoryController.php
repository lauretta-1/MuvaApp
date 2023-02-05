<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Resource\CategoryResource;
use App\Http\Resources\Collection\CategoryResourceCollection;
use App\Http\Requests\User\CreateCategoryRequest;
use App\Http\Requests\User\UpdateCategoryRequest;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::latest();
        return new CategoryResourceCollection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $request->validated();
        $user = User::whereUuid($request->user_uuid)->first();
        $category = Category::create([
            'user_id' => $user->id,
            'name' => $request->name
        ]);

        if($category){
            return new CategoryResource($category);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Something went wrong'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $category = Category::whereUuid($uuid)->first();

        if($category){
            return new CategoryResource($category);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Category does not exist!'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $uuid)
    {
        $category = Category::whereUuid($uuid)->first();

        if(!$category){
            return response()->json([
                'status' => 'failed',
                'message' => 'Category does not exist!'
            ], 404);
        }
        if($category->user_id !== auth()->user()->id){
            return response()->json([
                'status' => 'failed',
                'message' => 'Forbidden!'
            ], 403);
        }
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $category = Category::whereUuid($uuid)->first();

        if(!$category){
            return response()->json([
                'status' => 'failed',
                'message' => 'Category does not exist!'
            ], 404);
        }
        if($category->user_id !== auth()->user()->id){
            return response()->json([
                'status' => 'failed',
                'message' => 'Forbidden!'
            ], 403);
        }

        $category->delete();
        return ['status' => 'category Deleted!'];
    }
}
