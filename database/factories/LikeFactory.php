<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Like;

class LikeFactory extends Factory
{
    protected $model = Like::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'likeable_id' => \App\Models\Post::factory(),
            'likeable_type' => 'App\Models\Post',
        ];
    }
}
