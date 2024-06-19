<?php

use App\Models\Task;
use App\Models\User;

it('muestra la informacion de una tarea', function () {
    $task = Task::factory()->create([
        'name' => 'Tarea nueva',

    ]);

    $response = $this->get($task->path());

    $response->assertStatus(200);
    $response->assertSee('Tarea nueva');
});

it('crea una nueva tarea', function () {
    $this->withoutExceptionHandling();

    $user = User::factory()->create();

    $data = [
        'name' => 'Nueva tarea',
        'user_id' => $user->id,
        'priority' => 3, // Aquí puedes definir el valor de prioridad según tu lógica
    ];

    $response = $this->post('/tasks', $data);

    expect(Task::count())->toBe(1);
    expect(Task::first()->name)->toBe('Nueva tarea');
    expect(Task::first()->priority)->toBe(3); // Verificar que la prioridad se haya guardado correctamente

    $response->assertRedirect('/tasks');
});

it('actualizar una tarea', function () {
    $task = Task::factory()->create([
        'name' => 'Tarea vieja',
    ]);

    $data = [
        'name' => 'Tarea actualizada',
        'user_id' => $task->user_id,
    ];

    $response = $this->put($task->path(), $data);

    expect($task->fresh()->name)->toBe('Tarea actualizada');

});

it('actualizar el usuario de una tarea', function () {
    $this->withoutExceptionHandling();

    $task = Task::factory()->create([
        'name' => 'Tarea vieja',
    ]);
    $otroUsuario = User::factory()->create();
    $data = [
        'name' => 'Tarea vieja',
        'user_id' => $otroUsuario->id,
    ];

    $response = $this->put($task->path(), $data);

    expect($task->fresh()->user_id)->toBe($otroUsuario->id);

});

it('filtra las tareas por usuario', function () {
    // Crear dos usuarios
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    // Crear tareas para cada usuario
    $task1 = Task::factory()->create([
        'name' => 'Tarea de usuario 1',
        'user_id' => $user1->id,
    ]);
    $task2 = Task::factory()->create([
        'name' => 'Tarea de usuario 2',
        'user_id' => $user2->id,
    ]);

    // Realizar la solicitud para obtener las tareas del usuario 1
    $response = $this->get('/tasks?user_id='.$user1->id);

    // Verificar que la respuesta tiene un status 200
    $response->assertStatus(200);

    // Verificar que la respuesta contiene la tarea del usuario 1 y no la del usuario 2
    $response->assertSee('Tarea de usuario 1');
    $response->assertDontSee('Tarea de usuario 2');
});

it('marca una tarea como completada', function () {
    // Crear un usuario y una tarea asignada a ese usuario
    $user = User::factory()->create();
    $task = Task::factory()->create([
        'name' => 'Tarea incompleta',
        'user_id' => $user->id,
        'Complete' => false, // Usa 'Complete'
    ]);

    // Realizar la solicitud para marcar la tarea como completada
    $response = $this->post(route('tasks.complete', $task->id));

    // Verificar que la tarea ha sido marcada como completada
    expect($task->fresh()->Complete)->toBeTrue(); // Usa 'Complete'

    // Verificar que la respuesta redirige al índice de tareas
    $response->assertRedirect('/tasks');
});
function OrdenarTest()
{
    $this->withoutExceptionHandling();

    // Crear tareas con diferentes prioridades
    $task1 = Task::factory()->create([
        'name' => 'Tarea 1',
        'priority' => 2,
    ]);
    $task2 = Task::factory()->create([
        'name' => 'Tarea 2',
        'priority' => 1,
    ]);
    $task3 = Task::factory()->create([
        'name' => 'Tarea 3',
        'priority' => 3,
    ]);

    // Crear usuario de prueba
    $user = User::factory()->create();

    // Realizar solicitud GET a la ruta 'tasks.index' con parámetros de búsqueda y ordenamiento
    $response = $this->get(route('tasks.index'), [
        'search' => 'Tarea', // Filtro por nombre de tarea
        'user_id' => $user->id, // Filtro por usuario
        'order_by' => 'priority', // Ordenar por prioridad ascendente
    ]);

    // Asserts para verificar la respuesta
    $response->assertStatus(200); // Verificar que la respuesta sea exitosa (código 200)
    $response->assertViewIs('tasks.index'); // Verificar que la vista devuelta sea 'tasks.index'

    // Verificar que la vista contenga las variables adecuadas
    $response->assertViewHas('tasks');
    $response->assertViewHas('users');

    // Obtener las tareas ordenadas de la respuesta
    $tasksInResponse = $response->viewData('tasks');

    // Verificar que se devuelvan todas las tareas esperadas
    $this->assertCount(3, $tasksInResponse);

    // Verificar el orden correcto de las tareas por prioridad
    $this->assertEquals('Tarea 3', $tasksInResponse[0]->name);
    $this->assertEquals('Tarea 1', $tasksInResponse[1]->name);
    $this->assertEquals('Tarea 2', $tasksInResponse[2]->name);

    // Verificar que se aplican correctamente los filtros de búsqueda y usuario
    $this->assertEquals('Tarea', $response->original['search']);
    $this->assertEquals($user->id, $response->original['user_id']);
}
