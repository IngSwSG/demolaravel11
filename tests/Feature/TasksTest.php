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

 it('filtra las tareas por user_id y término de búsqueda', function () {
    $this->withoutExceptionHandling();

    // Arrange
    // Crear dos usuarios
    $user1 = User::factory()->create(['name' => 'Usuario 1']);
    $user2 = User::factory()->create(['name' => 'Usuario 2']);

    // Crear tareas para ambos usuarios
    $task1 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Tarea buscable para Usuario 1']);
    $task2 = Task::factory()->create(['user_id' => $user2->id, 'name' => 'Tarea buscable para Usuario 2']);
    $task3 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Tarea no buscable para Usuario 1']);

    // Act
    // Enviar una solicitud a la ruta index con filtro por user_id y término de búsqueda
    $response = $this->get('/tasks?user_id=' . $user1->id . '&search=buscable');

    // Assert
    // Verificar que la respuesta es OK
    $response->assertStatus(200);

    // Verificar que la respuesta contiene la tarea filtrada y no contiene las tareas no filtradas
    $response->assertSee($task1->name);
    $response->assertDontSee($task2->name);
    $response->assertDontSee($task3->name);
});


