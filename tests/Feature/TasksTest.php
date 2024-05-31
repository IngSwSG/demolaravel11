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




 // Nueva prueba para verificar el filtro por usuario Actividad 8
 //Usando la estrutura vista en Clase AAA

it('filtra las tareas por usuario', function () {
    // Arrange: Crear
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $task1 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Task 1 for User 1']);
    $task2 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Task 2 for User 1']);
    $task3 = Task::factory()->create(['user_id' => $user2->id, 'name' => 'Task for User 2']);

    // Act: Llamar unidad
    $response = $this->get(route('tasks.index', ['user_id' => $user1->id]));

    // Asserts: Verificar
    $response->assertStatus(200);
    $response->assertViewHas('tasks', function ($tasks) use ($task1, $task2, $task3) {
        return $tasks->contains($task1) && $tasks->contains($task2) && !$tasks->contains($task3);
    });
});