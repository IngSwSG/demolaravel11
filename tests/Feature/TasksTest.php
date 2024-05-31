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
