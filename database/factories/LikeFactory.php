<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Like;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //

            'user_id' => User::factory(), // or use User::factory()->create()->id
            'likeable_id' => $this->faker->randomNumber(),
            'likeable_type' => 'App\Models\Post', // Adjust as per your polymorphic setup
        ];
    }
}
