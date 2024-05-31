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

it('filtra tareas por user_id', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();


    // Crear tareas para diferentes usuarios
    $task1 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Tarea del usuario 1']);
    $task2 = Task::factory()->create(['user_id' => $user2->id, 'name' => 'Tarea del usuario 2']);
    $task3 = Task::factory()->create(['user_id' => $user3->id, 'name' => 'Tarea del usuario 3']);

    // Filtrar tareas por user_id
    $response = $this->get('/tasks?user_id=' . $user1->id);

    // Verificar que solo las tareas del usuario especificado están en la respuesta
    $response->assertStatus(200);
    $response->assertSee($task1->name);
    $response->assertDontSee($task2->name);
    $response->assertDontSee($task3->name);

});

// Prueba para verificar el filtro por búsqueda y usuario
it('filtra tareas por nombre y user_id', function () {
    $user1 = User::factory()->create();
    $user3 = User::factory()->create();

    // Crear tareas para diferentes usuarios
    $task1 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Tarea especial del usuario 1']);
    $task2 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Otra tarea del usuario 1']);
    $task3 = Task::factory()->create(['user_id' => $user3->id, 'name' => 'Tarea del usuario 3']);

    // Filtrar tareas por nombre y user_id
    $response = $this->get('/tasks?search=especial&user_id=' . $user1->id);

    $response->assertStatus(200);
    $response->assertSee($task1->name);
    $response->assertDontSee($task2->name);
    $response->assertDontSee($task3->name);
});