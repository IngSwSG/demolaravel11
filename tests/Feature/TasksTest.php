<?php

use App\Models\Task;
use App\Models\User;

it('muestra la informacion de una tarea', function () {
    $task = Task::factory()->create([
        'name' => 'Tarea nueva'

    ]);

    $response = $this->get($task->path());

    $response->assertStatus(200);
    $response->assertSee('Tarea nueva');
});

it('crea una nueva tarea', function (){
    $this->withoutExceptionHandling();

    $user = User::factory()->create();

    $data = [
        'name' => 'Nueva tarea',
        'user_id' => $user->id
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
       'name' => 'Tarea vieja'
   ]);

   $data = [
       'name' => 'Tarea actualizada',
       'user_id' => $task->user_id
   ];

   $response = $this->put($task->path(), $data);

   expect($task->fresh()->name)->toBe('Tarea actualizada');

});

it('actualizar el usuario de una tarea', function () {
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

 it('verifica la funcionalidad e filtro', function () {
    // Crear usuarios y tareas
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $task1 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Primera ']);
    $task2 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Segunda ']);
    $task3 = Task::factory()->create(['user_id' => $user2->id, 'name' => 'Tercera ']);
    $task4 = Task::factory()->create(['user_id' => $user2->id, 'name' => 'Cuarta']);

    // Escenario 1: Sin filtro
    $response = $this->get('/tasks');
    $response->assertStatus(200);
    $response->assertViewHas('tasks', function ($tasks) {
        return $tasks->count() === 4;
    });

    // Escenario 2: Filtrar por user_id
    $response = $this->get('/tasks?user_id=' . $user1->id);
    $response->assertStatus(200);
    $response->assertViewHas('tasks', function ($tasks) use ($user1) {
        return $tasks->count() === 2 && $tasks->every(function ($task) use ($user1) {
            return $task->user_id === $user1->id;
        });
    });

    // Escenario 3: Filtrar por término de búsqueda
    $response = $this->get('/tasks?search=Task');
    $response->assertStatus(200);
    $response->assertViewHas('tasks', function ($tasks) {
        return $tasks->count() === 3 && $tasks->contains('name', 'Task One') && $tasks->contains('name', 'Task Two');
    });

    // Escenario 4: Filtrar por user_id y término de búsqueda
    $response = $this->get('/tasks?user_id=' . $user1->id . '&search=Task');
    $response->assertStatus(200);
    $response->assertViewHas('tasks', function ($tasks) use ($user1) {
        return $tasks->count() === 1 && $tasks->first()->user_id === $user1->id && $tasks->first()->name === 'Task One';
    });
});

