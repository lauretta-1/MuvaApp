<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Requests\Category\CreateCategoryRequest;
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
        $categories = Category::latest()->get();
        return CategoryResource::collection($categories);
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
        $user = auth()->user();
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
    public function update(CreateCategoryRequest $request, $uuid)
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
        return ['status' => 'Category Deleted!'];
    }
}
