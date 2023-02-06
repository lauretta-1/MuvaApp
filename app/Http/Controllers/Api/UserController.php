<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\User\UserResource;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Str;
use JWTAuth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $users = User::latest()->get();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterUserRequest $request)
    {
        $user = User::create(array_merge(
            $request->validated(),
            ['password'=>bcrypt($request->password)]
        ));

        return new UserResource($user);
    }

    /**
     * log in a resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginUserRequest $request){
        $token_validity = 24 * 60;
        $this->guard()->factory()->setTTL($token_validity);
        if(!$token = $this->guard()->attempt($request->validated())){
            return response()->json(['error'=>'Unauthorized'], 401);
        }

        $token = $this->respondWithToken($token);
        $user = Auth::user();

        return response()->json([
            "status" => 'success',
            "message" => "Logged in successfully",
            "access_token" => $token,
            "user" => $user
        ]);
    }

    protected function respondWithToken($token){
        return response()->json([
            'token'=>$token,
            'token_type'=>'Bearer',
            'token_validity'=>$this->guard()->factory()->getTTL()*60
        ]);
    }
    protected function guard(){
        return Auth::guard();
    }

    // Logout
    public function logout(){
        $this->guard()->logout();

        return response()->json(['message'=>'User successfully logged out']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user_data = auth()->user();

        return response()->json([
            "status" => 'success',
            "message" => "User profile data",
            "data" => $user_data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request)
    {
        $user = auth()->user();

        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user = auth()->user();
        $user->delete();
        return ['status' => 'Account Deleted!'];
    }

    /**
     * Display a listing of the user posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function userPosts(){
        $user = auth()->user();
        $posts = $user->posts;

        return response()->json([
                'status' => 'success',
                'message' => 'Fetched Posts',
                'data' => $posts
            ], 200);
    }
}
