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
        'priority' => 1 // Agrega el campo priority
    ];

    $response = $this->post('/tasks', $data);

    expect(Task::count())->toBe(1);
    expect(Task::first()->name)->toBe('Nueva tarea');

    $response->assertRedirect('/tasks');
});

it('actualizar una tarea', function () {
    $task = Task::factory()->create([
        'name' => 'Tarea vieja'
    ]);
    
    $data = [
        'name' => 'Tarea actualizada',
        'user_id' => $task->user_id,
        'completed' => false,
        'priority' => 1 // Agrega el campo priority
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
        'user_id' => $otroUsuario->id,
        'completed' => false,
        'priority' => 1 // Agrega el campo priority
    ];
 
    $response = $this->put($task->path(), $data);
 
    expect($task->fresh()->user_id)->toBe($otroUsuario->id);
});

 it('filtra las tareas por usuario', function () {
    // Crear dos usuarios
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // Crear tareas para cada usuario
    $task1 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Tarea del usuario 1']);
    $task2 = Task::factory()->create(['user_id' => $user2->id, 'name' => 'Tarea del usuario 2']);
    $task3 = Task::factory()->create(['user_id' => $user1->id, 'name' => 'Otra tarea del usuario 1']);

    // Hacer una solicitud a la ruta 'tasks.index' filtrando por el primer usuario
    $response = $this->get(route('tasks.index', ['user_id' => $user1->id]));

    // Verificar que la respuesta contiene solo las tareas del primer usuario
    $response->assertStatus(200);
    $response->assertSee('Tarea del usuario 1');
    $response->assertSee('Otra tarea del usuario 1');
    $response->assertDontSee('Tarea del usuario 2');
});

it('marca una tarea como completada', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create();
    $task = Task::factory()->create(['user_id' => $user->id, 'completed' => false]);

    $response = $this->actingAs($user)->patch(route('tasks.complete', $task->id));

    $response->assertRedirect(route('tasks.index'));
    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'completed' => true,
    ]);
});

it('ordena las tareas por prioridad', function () {
    // Crear tres tareas con diferentes prioridades
    Task::factory()->create(['name' => 'Tarea 1', 'priority' => 3]);
    Task::factory()->create(['name' => 'Tarea 2', 'priority' => 1]);
    Task::factory()->create(['name' => 'Tarea 3', 'priority' => 2]);

    // Hacer una solicitud a la ruta 'tasks.index' sin filtrar por usuario
    $response = $this->get(route('tasks.index'));

    // Verificar que las tareas se muestren en el orden correcto de prioridad
    $response->assertSeeInOrder(['Tarea 2', 'Tarea 3', 'Tarea 1']);
});