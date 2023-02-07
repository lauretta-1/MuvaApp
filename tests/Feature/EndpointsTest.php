<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use JWTAuth;
use Illuminate\Support\Facades\Hash;

class EndpointsTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test for register user endpoint.
     *
     * @return void
     */
    public function test_register(){
        $userData = [
            "name" => "John John",
            "email" => "john@example.com",
            "password" => "demo12345",
            "password_confirmation" => "demo12345"
        ];

        $response = $this->post('api/v1/user/register', $userData);

        $response->assertStatus(201);
    }

    /**
     * A basic feature test for login endpoint.
     *
     * @return void
     */
    public function test_login()
    {
        $userData = [
            "email" => config('api.apiEmail'),
            "password" => config('api.apiPassword'),
        ];

        $response = $this->post('api/v1/user/login', $userData);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for logout endpoint.
     *
     * @return void
     */
    public function test_logout(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->post('api/v1/user/logout?token='.$token, []);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for forgot password endpoint.
     *
     * @return void
     */
    public function test_forgot_password(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $data = [
            'email' => config('api.apiEmail')
        ];

        $response = $this->post('api/v1/user/forgot-password?token='.$token, $data, []);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for password reset endpoint.
     *
     * @return void
     */
    public function test_password_reset(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $data = [
            'token' => "lLt1e3XoCKcv9zZ7UkyblI3eKFkbszPi3XcdLx9hO09lLYG3eq3TR5RcYcD7",
            "password" => "demo12345",
            "password_confirmation" => "demo12345"
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$token,
        ])->post(route('password-reset'), $data);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for list users endpoint.
     *
     * @return void
     */
    public function test_users_list(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->get('api/v1/users?token='.$token, []);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for get a user endpoint.
     *
     * @return void
     */
    public function test_user_details(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->get('api/v1/user/profile?token='.$token, []);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for list a user posts endpoint.
     *
     * @return void
     */
    public function test_user_posts(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->get('api/v1/user/posts?token='.$token, []);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for update user details endpoint.
     *
     * @return void
     */
    public function test_user_update(){
        $userData = [
            "name" => "John Doe",
            "email" => "john@example.com",
        ];

        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->patch('api/v1/user/update?token='.$token, $userData, []);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for delete user endpoint.
     *
     * @return void
     */
    public function test_user_delete(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->delete('api/v1/user/delete?token='.$token, []);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for list categories endpoint.
     *
     * @return void
     */
    public function test_category_list(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->get('api/v1/categories?token='.$token, []);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for create category endpoint.
     *
     * @return void
     */
    public function test_store_category(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $categoryData = [
            "user_id" => $user->id,
            "name" => "Programming",
        ];

        $response = $this->post('api/v1/categories?token='.$token, $categoryData, []);

        $response->assertStatus(201);
    }

    /**
     * A basic feature test for update category endpoint.
     *
     * @return void
     */
    public function test_update_category(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $category_uuid = Category::where('user_id', $user->id)->first()->uuid;
        $categoryData = [
            "name" => "Programming",
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$token,
        ])->patch(route('categories.update', $category_uuid), $categoryData);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for get a category endpoint.
     *
     * @return void
     */
    public function test_show_category_details(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $category_uuid = Category::where('user_id', $user->id)->first()->uuid;

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$token,
        ])->get(route('categories.show', $category_uuid));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for delete category endpoint.
     *
     * @return void
     */
    public function test_category_delete(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $category_uuid = Category::where('user_id', $user->id)->first()->uuid;

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$token,
        ])->delete(route('categories.destroy', $category_uuid));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for list posts endpoint.
     *
     * @return void
     */
    public function test_post_list(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->get('api/v1/posts?token='.$token, []);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for store/create post endpoint.
     *
     * @return void
     */

    public function test_store_post(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $category_uuid = Category::latest()->first()->uuid;

        $postData = [
            "category_uuid" => $category_uuid,
            "title" => "Programming",
            "body" => "Programming",
        ];

        $response = $this->post('api/v1/posts?token='.$token, $postData, []);

        $response->assertStatus(201);
    }

    /**
     * A basic feature test for post update endpoint.
     *
     * @return void
     */
    public function test_update_post(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $post_uuid = Post::where('user_id', $user->id)->first()->uuid;
        $postData = [
            "title" => "Programming",
            "body" => "Programming",
        ];

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$token,
        ])->patch(route('posts.update', $post_uuid), $postData);

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for get a post endpoint.
     *
     * @return void
     */
    public function test_post_details(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $post_uuid = Post::where('user_id', $user->id)->first()->uuid;

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$token,
        ])->get(route('posts.show', $post_uuid));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test for post delete endpoint.
     *
     * @return void
     */
    public function test_post_delete(){
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $post_uuid = Post::where('user_id', $user->id)->first()->uuid;

        $response = $this->withHeaders([
            'Authorization' => "Bearer ".$token,
        ])->delete(route('posts.destroy', $post_uuid));

        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
