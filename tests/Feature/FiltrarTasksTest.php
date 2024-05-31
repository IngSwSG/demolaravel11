<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FiltarTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_filtro()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $task1 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Tarea por usuario1']);
        $task2 = Task::factory()->create(['user_id' => $user2->id, 'name' => 'Tarea por usuario2']);
        $task3 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Otra tarea para el usuario']);

        
        $response = $this->get('/tasks?user_id=' . $user1->id);

        $response->assertStatus(200);
        $response->assertSee($task1->name);
        $response->assertSee($task3->name);
        $response->assertDontSee($task2->name); 
        $response = $this->get('/tasks?user_id=' . $user2->id);

        $response->assertStatus(200);
        $response->assertSee($task2->name);
        $response->assertDontSee($task1->name); 
        $response->assertDontSee($task3->name);
    }
}
