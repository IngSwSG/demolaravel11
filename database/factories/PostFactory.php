<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use app\Models\Post;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostFactory extends Factory
{
   
        protected $model = Post::class;
    
        public function definition()
        {
            return [
                'title' => $this->faker->sentence,
                'body' => $this->faker->paragraph,
            ];
        }
    
}
