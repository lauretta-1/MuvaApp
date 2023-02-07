<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use JWTAuth;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EndpointsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic unit test for create user endpoint.
     *
     * @return void
     */
    public function test_create_user_endpoint()
    {
        $userData = [
            "name" => "John Doe",
            "email" => "doe@example.com",
            "password" => "demo12345",
            "password_confirmation" => "demo12345"
        ];

        $this->json('POST', 'api/v1/user/register', $userData, ['Accept' => 'application/json'])
        ->assertStatus(201)
        ->assertJsonStructure([
            "data"=> [
                "uuid",
                "attributes"=> [
                    "name",
                    "eamil",
                    "categories",
                    "posts",
                    "created_at"
                ]
            ],

        ]);
    }

    /**
    * Login as default API user and get token back.
    *
    * @return void
    */
    public function testLogin()
    {
        $userData = [
            "email" => config('api.apiEmail'),
            "password" => config('api.apiPassword'),
        ];

        $response = $this->json('POST', 'api/v1/user/login', $userData, ['Accept' => 'application/json']);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                "status",
                "message",
                "access_token",
                "user"
            ]);
    }

    /**
    * Test logout.
    *
    * @return void
    */
    public function testLogout()
    {
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $response = $this->json('POST', 'api/v1/user/logout?token='.$token, []);

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'User successfully logged out'
            ]);
    }

    /**
     * A basic unit test for list posts endpoint.
     *
     * @return void
     */
    public function test_list_categories_endpoint()
    {
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $this->json('GET', 'api/v1/categories?token='.$token, [])
        ->assertStatus(200);
    }

    /**
     * A basic unit test for create user endpoint.
     *
     * @return void
     */
    public function test_create_category_endpoint()
    {
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $categoryData = [
            "user_id" => $user->id,
            "name" => "Laravel",
        ];

        $this->json('POST', 'api/v1/categories?token='.$token, $categoryData, [])
        ->assertStatus(201);
    }

    /**
     * A basic unit test for create post endpoint.
     *
     * @return void
     */
    public function test_create_post_endpoint()
    {
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);
        $category = Category::latest()->first();

        $postData = [
            "title" => "What is HTML?",
            "body" => "HTML stands for HyperText Markup Language",
            "category_uuid" => $category->uuid,
        ];

        $this->json('POST', 'api/v1/posts?token='.$token, $postData, [])
        ->assertStatus(201);
    }

    /**
     * A basic unit test for list posts endpoint.
     *
     * @return void
     */
    public function test_list_posts_endpoint()
    {
        $user = User::where('email', config('api.apiEmail'))->first();
        $token = JWTAuth::fromUser($user);

        $this->json('GET', 'api/v1/posts?token='.$token, [])
        ->assertStatus(200);
    }


    // /**
    //  * A basic unit test example.
    //  *
    //  * @return void
    //  */
    // public function test_example()
    // {
    //     $this->assertTrue(true);
    // }
}
