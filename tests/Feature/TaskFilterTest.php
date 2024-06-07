<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskFilterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test_filtro.
     *
     * @return void
     */
    public function test_filtro()
    {
        // Creamos dos usuarios
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Generar tareas para cada usuario
        $task1 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Task for user1']);
        $task2 = Task::factory()->create(['user_id' => $user2->id, 'name' => 'Task for user2']);
        $task3 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Otra tarea para el usuario']);

        // Hacer una solicitud GET para filtrar tareas por el primer usuario
        $response = $this->get('/tasks?user_id=' . $user1->id);

        // Verificar que la respuesta es correcta
        $response->assertStatus(200);
        $response->assertSee($task1->name);
        $response->assertSee($task3->name);
        $response->assertDontSee($task2->name); // Esta tarea no debe estar en la respuesta

        // Hacer una solicitud GET para filtrar tareas por el segundo usuario
        $response = $this->get('/tasks?user_id=' . $user2->id);

        // Verificar que la respuesta es correcta
        $response->assertStatus(200);
        $response->assertSee($task2->name);
        $response->assertDontSee($task1->name); // Estas tareas no deben estar en la respuesta
        $response->assertDontSee($task3->name);
    }
}
