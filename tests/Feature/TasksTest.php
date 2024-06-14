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
 it('marca una tarea como completa', function () {
    // Crear una tarea
    $task = Task::factory()->create([
        'name' => 'Tarea de prueba',
        'completed' => 0, // Asegúrate de que el valor predeterminado de completed sea 0
    ]);

    // Verificar que la tarea fue creada con éxito
    $this->assertDatabaseHas('tasks', [
        'name' => 'Tarea de prueba',
        'completed' => 0,
    ]);

    // Simular la acción de completar la tarea
$response = $this->patch(route('tasks.complete', ['task' => $task->id]), [
    'name' => $task->name,
    'user_id' => $task->user_id,
    'completed' => 1, // Cambiar el estado de completed a 1 (completo)
]);

    // Verificar que la tarea se actualizó correctamente
    $response->assertRedirect(route('tasks.index'));
    $this->assertDatabaseHas('tasks', [
        'name' => 'Tarea de prueba',
        'completed' => 1, // Verificar que el estado de completed cambió a 1
    ]);
});



   


