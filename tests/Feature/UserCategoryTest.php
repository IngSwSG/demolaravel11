<?php

use App\Models\Task;
use App\Models\User;

it('Filtra las tareas por usuario', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $task1 = Task::factory()->create([
        'name' => 'Tarea del usuario 1',
        'user_id' => $user1->id
    ]);

    $task2 = Task::factory()->create([
        'name' => 'Tarea del usuario 2',
        'user_id' => $user2->id
    ]);

    $response = $this->actingAs($user1)->get('/tasks?user_id=' . $user1->id);

    $response->assertStatus(200);
    $response->assertSee('Tarea del usuario 1');
    $response->assertDontSee('Tarea del usuario 2');
});