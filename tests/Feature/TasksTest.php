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
        'priority' => 3
    ];

    $response = $this->post('/tasks', $data);

    $response->assertRedirect('/tasks');
    $this->assertDatabaseHas('tasks', [
        'name' => 'Nueva tarea',
        'user_id' => $user->id,
        'priority' => 3
    ]);

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


 it('ProbarFiltradoDeUsuarios', function () {

    // Crear dos instancias de usuario
    $usuario1 = User::factory()->create();
    $usuario2 = User::factory()->create();

    // Generar tareas asociadas a cada usuario
    $tarea1 = Task::factory()->create(['user_id' => $usuario1->id, 'name' => 'Comprar pan']);
    $tarea2 = Task::factory()->create(['user_id' => $usuario1->id, 'name' => 'Comprar Natilla']);
    $tarea3 = Task::factory()->create(['user_id' => $usuario2->id, 'name' => 'Hacer cafe']);

    // Realizar una solicitud a la ruta de índice aplicando el filtro por user_id
    $respuesta = $this->get(route('tasks.index', ['user_id' => $usuario1->id]));

    // Verificar que la tarea del usuario1 está presente en la respuesta
    $respuesta->assertSee($tarea1->name);
    $respuesta->assertSee($tarea2->name);

    // Verificar que la tarea del usuario2 no está presente en la respuesta
    $respuesta->assertDontSee($tarea3->name);

});

it('marca una tarea como completada', function () {
    $this->withoutExceptionHandling();

    $task = Task::factory()->create([
        'completed' => false,
    ]);

    $response = $this->patch(route('tasks.complete', $task));

    expect($task->fresh()->completed)->toBeTrue();

    $response->assertRedirect(route('tasks.index'));
});

it('Probar ordenamiento de tareas por prioridad',function() {
    $user = User::factory()->create();
        $data = [
            'name' => 'Nueva tarea',
            'user_id' => $user->id,
            'priority' => 3
        ];

        $response = $this->post('/tasks', $data);

        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', [
            'name' => 'Nueva tarea',
            'user_id' => $user->id,
            'priority' => 3
        ]);
});

it('Las tareas son ordenadas por prioridad', function() {
    $user = User::factory()->create();
        $task1 = Task::factory()->create(['user_id' => $user->id, 'priority' => 1, 'name' => 'Tarea 1']);
        $task2 = Task::factory()->create(['user_id' => $user->id, 'priority' => 3, 'name' => 'Tarea 2']);
        $task3 = Task::factory()->create(['user_id' => $user->id, 'priority' => 2, 'name' => 'Tarea 3']);

        $response = $this->get('/tasks');

        $response->assertSeeInOrder(['Tarea 2', 'Tarea 3', 'Tarea 1']);
});
