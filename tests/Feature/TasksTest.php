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
 
 it('verificador del filtro', function () {
   
    $user = User::factory()->create();
   

    Task::factory()->create(['name' => 'Tarea A']);
    Task::factory()->create(['name' => 'Tarea X', 'user_id' => $user->id]);

    $response = $this->get('/tasks?search=X&user_id=' . $user->id);
    $response->assertStatus(200);
    $response->assertSee('Tarea X');
    $response->assertDontSee('Tarea A');
   

  
});
it('verifica que el atributo isCompleted de la tarea se establece en true al actualizar', function () {
    // Arrange
    $task = Task::factory()->create([
        'name' => 'Tarea original',
        'user_id' => User::factory()->create()->id,
        'isCompleted' => false,
    ]);

    $updatedData = [
        'name' => 'Tarea actualizada',
        'user_id' => $task->user_id,
        'isCompleted' => true,
    ];

    // Act
    $response = $this->put(route('tasks.update', $task), $updatedData);

    // Assert
    $response->assertRedirect(route('tasks.index'));

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'name' => 'Tarea actualizada',
        'isCompleted' => true,
    ]);
});

