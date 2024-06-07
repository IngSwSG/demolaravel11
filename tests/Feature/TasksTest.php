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
 it('filtra tareas por usuario', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $task1 = Task::factory()->create(['user_id' => $user1->id]);
    $task2 = Task::factory()->create(['user_id' => $user2->id]);
    $task3 = Task::factory()->create(['user_id' => $user1->id]);

    $response = $this->get('/tasks?user_id=' . $user1->id);

    $response->assertStatus(200);
    $response->assertViewHas('tasks', function ($tasks) use ($user1) {
        return $tasks->every(function ($task) use ($user1) {
            return $task->user_id == $user1->id;
        });
    });

    $tasks = $response->viewData('tasks');
    expect($tasks->count())->toBe(2);
});
it('marca una tarea como completada', function () {
    $this->withoutExceptionHandling();

    // Crear usuario y tarea
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id, 'completed' => false]);

    expect($task->completed)->toBe(false);

    $response = $this->patch(route('tasks.complete', $task));

    $task->refresh();

   
    expect($task->completed)->toBe(true);

    $response->assertRedirect('/tasks');
});
