<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        // User::factory(10)->create();
        DB::table('users')->truncate();
        DB::table('tasks')->truncate();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
        ]);

        Task::factory(3)->create([
            'user_id' => $user->id,
            'completed' => false,
            
        ]);

        Task::factory(2)->create([
            'user_id' => $user2->id,
            'completed' => false,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
