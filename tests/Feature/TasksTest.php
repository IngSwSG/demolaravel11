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
    $this->withoutExceptionHandling();

    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Task::factory()->create([
        'user_id' => $user1->id,
        'name' => 'Tarea del Usuario 1'
    ]);

    Task::factory()->create([
        'user_id' => $user2->id,
        'name' => 'Tarea del Usuario 2'
    ]);

    $response = $this->get('/tasks?user_id=' . $user1->id);

    $response->assertStatus(200);
    $response->assertSee('Tarea del Usuario 1');
    $response->assertDontSee('Tarea del Usuario 2');
});

/** @test */
it('it_marks_task_as_completed', function()
{
    $this->withoutExceptionHandling();

    $task = Task::factory()->create([
        'completed' => false,
        'name' => 'Tarea Incompleta'
    ]);

    $response = $this->post($task->path() . '/complete');

    $response->assertStatus(302);
    $response->assertRedirect('/tasks');
    
    $this->assertTrue($task->fresh()->completed);

    $response = $this->get('/tasks');
    $response->assertStatus(200);
    $response->assertSee('Tarea Incompleta');
});
