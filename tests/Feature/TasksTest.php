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

 
 it('ProbarFiltroUsuarios', function () {

    // Arrange
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $task1 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Task for user 1']);
    $task2 = Task::factory()->create(['user_id' => $user2->id, 'name' => 'Task for user 2']);
    $task3 = Task::factory()->create(['user_id' => $user2->id, 'name' => 'Task for user 2']);
    

    // Act
    $response = $this->get(route('tasks.index', ['user_id' => $user2->id]));

    // Assert 
    $response->assertSee($task2->name);
    $response->assertSee($task3->name);

    $response->assertDontSee($task1->name);

 });
 
 it('Probar tarea completada', function(){
    // Arrange
    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id, 'completed' => false]);

    // Act
    $response = $this->put(route('tasks.complete', $task));

    // Assert
    $response->assertRedirect(route('tasks.index'));
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'completed' => true,
    ]);
 });
