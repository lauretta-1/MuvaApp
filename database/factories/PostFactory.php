<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;
use App\Models\Category;
use App\Models\User;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::all()->pluck('id')->toArray();
        $categories = Category::all()->pluck('id')->toArray();
        return [
            'title'=>$this->faker->name(),
            'body'=>$this->faker->sentence(),
            'user_id' => $this->faker->randomElement($users),
            'category_id' => $this->faker->randomElement($categories)

        ];
    }
}
