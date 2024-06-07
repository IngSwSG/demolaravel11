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

 
 it('filtra tareas por user_id y nombre', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    
    $task1 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Tarea user 1']);
    $task2 = Task::factory()->create(['user_id' => $user2->id, 'name' => 'Tarea user 2']);
    $task3 = Task::factory()->create(['user_id' => $user3->id, 'name' => 'Tarea user 3']);
    $task4 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Tarea especial del usuario 1']);
    $task5 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Otra tarea del usuario 1']);
    $task6 = Task::factory()->create(['user_id' => $user3->id, 'name' => 'Tarea especial del usuario 3']);

    
    $response = $this->get('/tasks?user_id=' . $user1->id);

    
    $response->assertStatus(200);
    $response->assertSee($task1->name);
    $response->assertSee($task4->name);
    $response->assertSee($task5->name);
    $response->assertDontSee($task2->name);
    $response->assertDontSee($task3->name);
    $response->assertDontSee($task6->name);

   
    $response = $this->get('/tasks?search=especial&user_id=' . $user1->id);

    $response->assertStatus(200);
    $response->assertSee($task4->name);
    $response->assertDontSee($task1->name);
    $response->assertDontSee($task2->name);
    $response->assertDontSee($task3->name);
    $response->assertDontSee($task5->name);
    $response->assertDontSee($task6->name);
});

it('marca una tarea como completada', function () {
    $task = Task::factory()->create([
        'completed' => false
    ]);

    $response = $this->patch(route('tasks.complete', $task));

    $response->assertRedirect(route('tasks.index'));
    expect($task->fresh()->completed)->toBeTrue();
});
