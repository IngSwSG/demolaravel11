<?php

use App\Models\Task;
use App\Models\User;

it('muestra la informacion de una tarea', function () {
    $task = Task::factory()->create([
        'name' => 'Tarea nueva',

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
        'user_id' => $user->id,
    ];

    $response = $this->post('/tasks', $data);

    expect(Task::count())->toBe(1);
    expect(Task::first()->name)->toBe('Nueva tarea');

    // $this->assertDatabaseHas('tasks', [
    //     'name' => 'Nueva tarea'
    // ]);

    $response->assertRedirect('/tasks');

});

it('actualizar una tarea', function () {
    $task = Task::factory()->create([
        'name' => 'Tarea vieja',
    ]);

    $data = [
        'name' => 'Tarea actualizada',
        'user_id' => $task->user_id,
    ];

    $response = $this->put($task->path(), $data);

    expect($task->fresh()->name)->toBe('Tarea actualizada');

});

it('actualizar el usuario de una tarea', function () {
    $this->withoutExceptionHandling();

    $task = Task::factory()->create([
        'name' => 'Tarea vieja',
    ]);
    $otroUsuario = User::factory()->create();
    $data = [
        'name' => 'Tarea vieja',
        'user_id' => $otroUsuario->id,
    ];

    $response = $this->put($task->path(), $data);

    expect($task->fresh()->user_id)->toBe($otroUsuario->id);

});

it('filtra las tareas por usuario', function () {
    // Crear dos usuarios
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // Crear tareas para cada usuario
    $task1 = Task::factory()->create([
        'name' => 'Tarea de usuario 1',
        'user_id' => $user1->id,
    ]);
    $task2 = Task::factory()->create([
        'name' => 'Tarea de usuario 2',
        'user_id' => $user2->id,
    ]);

    // Realizar la solicitud para obtener las tareas del usuario 1
    $response = $this->get('/tasks?user_id='.$user1->id);

    // Verificar que la respuesta tiene un status 200
    $response->assertStatus(200);

    // Verificar que la respuesta contiene la tarea del usuario 1 y no la del usuario 2
    $response->assertSee('Tarea de usuario 1');
    $response->assertDontSee('Tarea de usuario 2');
});
