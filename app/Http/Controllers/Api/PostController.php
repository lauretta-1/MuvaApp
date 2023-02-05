<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Resource\PostResource;
use App\Http\Resources\Collection\PostResourceCollection;
use App\Http\Requests\Post\CreatePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest();
        return new PostResourceCollection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $request->validated();
        $category = Category::whereUuid($request->category_uuid)->first();

        $post = Post::create([
            'user_id' => auth()->user()->id,
            'category_id' => $category->id,
            'title' => $request->title,
            'body' => $request->body
        ]);

        if($post){
            return new PostResource($post);
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
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $post = Post::whereUuid($uuid)->first();

        if($post){
            return new PostResource($post);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Post does not exist!'
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
    public function update(UpdatePostRequest $request, $uuid)
    {
        $post = Post::whereUuid($uuid)->first();

        if(!$post){
            return response()->json([
                'status' => 'failed',
                'message' => 'Post does not exist!'
            ], 404);
        }
        if($post->user_id !== auth()->user()->id){
            return response()->json([
                'status' => 'failed',
                'message' => 'Forbidden!'
            ], 403);
        }
        $post->update($request->validated());

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $post = Post::whereUuid($uuid)->first();

        if(!$post){
            return response()->json([
                'status' => 'failed',
                'message' => 'Post does not exist!'
            ], 404);
        }
        if($post->user_id !== auth()->user()->id){
            return response()->json([
                'status' => 'failed',
                'message' => 'Forbidden!'
            ], 403);
        }

        $post->delete();
        return ['status' => 'Post Deleted!'];
    }
}
