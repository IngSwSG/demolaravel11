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
        'priority' => 2 
    ];

    $response = $this->post('/tasks', $data);

    expect(Task::count())->toBe(1);
    expect(Task::first()->name)->toBe('Nueva tarea');
    expect(Task::first()->priority)->toBe(2);

    // $this->assertDatabaseHas('tasks', [
    //     'name' => 'Nueva tarea'
    // ]);

     $response->assertRedirect('/tasks');

});

it('actualizar una tarea', function () {
   $task = Task::factory()->create([
       'name' => 'Tarea vieja',
       'priority' => 1
   ]);
   
   $data = [
       'name' => 'Tarea actualizada',
       'user_id' => $task->user_id,
       'priority' => 3
   ];

   $response = $this->put($task->path(), $data);

   expect($task->fresh()->name)->toBe('Tarea actualizada');

});

it('actualiza el usuario de una tarea', function () {
    $this->withoutExceptionHandling();

    $task = Task::factory()->create([
        'name' => 'Tarea vieja',
        'priority' => 1 // Establece una prioridad inicial
    ]);
    $otroUsuario = User::factory()->create();
    $data = [
        'name' => 'Tarea vieja',
        'user_id' => $otroUsuario->id,
        'priority' => 1 // Mantén la prioridad igual para esta prueba
    ];

    $response = $this->put($task->path(), $data);

    expect($task->fresh()->user_id)->toBe($otroUsuario->id);
    expect($task->fresh()->priority)->toBe(1); // Verifica que la prioridad no haya cambiado
});



it('muestra la informacion de una tarea con prioridad', function () {
    $task = Task::factory()->create([
        'name' => 'Tarea con prioridad',
        'priority' => 3 // Establece la prioridad a 3 para la prueba
    ]);

    $response = $this->get($task->path());

    $response->assertStatus(200);
    $response->assertSee('Tarea con prioridad');
    $response->assertSee('Prioridad: 3'); // Verifica que la prioridad se muestre correctamente
});

