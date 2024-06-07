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
  // Creamos un usuario y tareas asociadas
  $user = User::factory()->create();
  $task1 = Task::factory()->create([
      'name' => 'Tarea nueva',
      'user_id' => $user->id,
  ]);

  // Creamos tareas adicionales para pruebas de filtrado
  $task2 = Task::factory()->create(['name' => 'Buscar esta tarea', 'user_id' => $user->id]);
  $task3 = Task::factory()->create(['name' => 'Otra tarea']);

  // Realizamos la petición GET a la ruta /tasks con el user_id y el término de búsqueda
  $response = $this->get(route('tasks.index', ['user_id' => $user->id, 'search' => 'Buscar']));

  // Verificamos que el estado de la respuesta sea 200
  $response->assertStatus(200);
  // Verificamos que la respuesta contenga la tarea que coincide con la búsqueda y pertenece al usuario, y no la otra
  $response->assertSee('Buscar esta tarea');
  $response->assertDontSee('Otra tarea');
});

it('verifica funcionalidad de completado', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create();
    $task = Task::factory()->create([
        'name' => 'Tarea1',
        'user_id' => $user->id,
        'estado' => false
    ]);

    $this->patch("/tasks/{$task->id}/complete")
        ->assertStatus(200);

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'estado' => true,
    ]);
 });
