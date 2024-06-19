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
        'user_id' => $user->id,
        'prioridad' => 2
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

 it('filtra las tareas por usuario', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    Task::factory()->create(['name' => 'Tarea 1', 'user_id' => $user1->id]);
    Task::factory()->create(['name' => 'Tarea 2', 'user_id' => $user2->id]);
    Task::factory()->create(['name' => 'Tarea 3', 'user_id' => $user1->id]);

    $response = $this->get('/tasks?user_id=' . $user1->id);

    $response->assertStatus(200);
    $response->assertSee('Tarea 1');
    $response->assertDontSee('Tarea 2');
    $response->assertSee('Tarea 3');
});

it('ordena las tareas por prioridad', function () {
    $user = User::factory()->create();

    Task::factory()->create(['name' => 'Tarea Baja Prioridad', 'user_id' => $user->id, 'prioridad' => 1]);
    Task::factory()->create(['name' => 'Tarea Media Prioridad', 'user_id' => $user->id, 'prioridad' => 2]);
    Task::factory()->create(['name' => 'Tarea Alta Prioridad', 'user_id' => $user->id, 'prioridad' => 3]);

    $response = $this->get('/tasks');

    $response->assertStatus(200);
    $response->assertSeeInOrder(['Tarea Alta Prioridad', 'Tarea Media Prioridad', 'Tarea Baja Prioridad']);
});
