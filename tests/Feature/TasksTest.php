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

 /******Actividad 8********/

 it('Verifica Filtro Por Usuario', function () {
    $this->withoutExceptionHandling();

    // Arrange
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $task1 = Task::factory()->create(['user_id' => $user1->id]);
    $task2 = Task::factory()->create(['user_id' => $user2->id]);
    $task3 = Task::factory()->create(['user_id' => $user2->id]);

    // Act
    $response = $this->get(route('tasks.index', ['user_id' => $user1->id]));


    // Assert
    $response->assertStatus(200);

    $response->assertViewHas('tasks', function ($tasks) use ($user1) {
        foreach ($tasks as $task) {
            if ($task->user_id !== $user1->id) {
                return false;
            }
        }
        return $tasks->count() === 1;
    });
 
 });

 /******Actividad 9*********/

 it('marca una tarea como completada', function () {

    $this->withoutExceptionHandling();

    // Arrange
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'user_id' => $user->id,
        'completed' => false,
        'name' => 'Ver Avengers EndGame',
    ]);

    // Act
    $response = $this->put(route('tasks.complete', $task));


    // Assert
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'completed' => true,
    ]);
});

/***Prioridades***/


it('tareas ordenadas por prioridad de mayor a menor', function () {
    $this->withoutExceptionHandling();

    // Arrange
    $user = User::factory()->create();
    $task1 = Task::factory()->create(['user_id' => $user->id, 'priority' => 1]);
    $task2 = Task::factory()->create(['user_id' => $user->id, 'priority' => 3]);
    $task3 = Task::factory()->create(['user_id' => $user->id, 'priority' => 2]);

    // Act
    $response = $this->get(route('tasks.index'));

    // Assert
    $response->assertStatus(200);

    $response->assertSeeInOrder([
        'Prioridad: 3', 
        'Prioridad: 2', 
        'Prioridad: 1'
    ]);
});
  
