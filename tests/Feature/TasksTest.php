<?php

use App\Models\Task;
use App\Models\User;

it('muestra la información de una tarea', function () {
    $task = Task::factory()->create([
        'name' => 'Tarea nueva'
    ]);

    $response = $this->get($task->path());

    $response->assertStatus(200);
    $response->assertSee('Tarea nueva');
});

it('crea una nueva tarea', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create();

    $data = [
        'name' => 'Nueva tarea',
        'user_id' => $user->id
    ];

    $response = $this->post('/tasks', $data);

    expect(Task::count())->toBe(1);
    expect(Task::first()->name)->toBe('Nueva tarea');

    $response->assertRedirect('/tasks');
});

it('actualiza una tarea', function () {
    $task = Task::factory()->create([
        'name' => 'Tarea vieja'
    ]);

    $data = [
        'name' => 'Tarea actualizada',
        'user_id' => $task->user_id
    ];

    $response = $this->put($task->path(), $data);

    expect($task->fresh()->name)->toBe('Tarea actualizada');
});

it('actualiza el usuario de una tarea', function () {
    $this->withoutExceptionHandling();

    $task = Task::factory()->create([
        'name' => 'Tarea vieja'
    ]);

    $otroUsuario = User::factory()->create();
    $data = [
        'name' => 'Tarea vieja',
        'user_id' => $otroUsuario->id
    ];

    $response = $this->put($task->path(), $data);

    expect($task->fresh()->user_id)->toBe($otroUsuario->id);
});

it('filtra tareas por usuario', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Task::factory()->count(3)->create(['user_id' => $user1->id]);
    Task::factory()->count(2)->create(['user_id' => $user2->id]);

    $responseUser1 = $this->get('/tasks?user_id=' . $user1->id);
    $responseUser2 = $this->get('/tasks?user_id=' . $user2->id);

    $responseUser1->assertStatus(200);
    $responseUser2->assertStatus(200);

    $responseUser1->assertSee('Tarea');
    $responseUser2->assertSee('Tarea');
});

