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
        'priority' => 1 
    ];

    $response = $this->post('/tasks', $data);

    expect(Task::count())->toBe(1);
    expect(Task::first()->name)->toBe('Nueva tarea');

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
        'priority' => 1 
    ];

    $response = $this->put($task->path(), $data);

    expect($task->fresh()->name)->toBe('Tarea actualizada');
});

it('actualizar el usuario de una tarea', function () {
    $this->withoutExceptionHandling();

    $task = Task::factory()->create([
        'name' => 'Tarea vieja',
        'priority' => 1 
    ]);
    $otroUsuario = User::factory()->create();
    $data = [
        'name' => 'Tarea vieja',
        'user_id' => $otroUsuario->id,
        'priority' => 1 
    ];

    $response = $this->put($task->path(), $data);

    expect($task->fresh()->user_id)->toBe($otroUsuario->id);
});

it('filtra las tareas por usuario', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $task1 = Task::factory()->create([
        'name' => 'Tarea del usuario 1',
        'user_id' => $user1->id,
        'priority' => 1 
    ]);

    $task2 = Task::factory()->create([
        'name' => 'Tarea del usuario 2',
        'user_id' => $user2->id,
        'priority' => 1 
    ]);

    $response = $this->get('/tasks?user_id=' . $user1->id);

    $response->assertStatus(200);
    $response->assertSee('Tarea del usuario 1');
    $response->assertDontSee('Tarea del usuario 2');
    
    $response = $this->get('/tasks?user_id=' . $user2->id);

    $response->assertStatus(200);
    $response->assertSee('Tarea del usuario 2');
    $response->assertDontSee('Tarea del usuario 1');
});

it('marca una tarea como completada', function () {
    $task = Task::factory()->create([
        'completed' => false,
        'priority' => 1 
    ]);

    $response = $this->patch(route('tasks.complete', $task->id));

    $response->assertRedirect();
    $this->assertEquals(1, $task->fresh()->completed);
});

it('ordena las tareas por prioridad', function () {
    
    $user = User::factory()->create();

    
    $task1 = Task::factory()->create(['priority' => 1, 'user_id' => $user->id]);
    $task2 = Task::factory()->create(['priority' => 2, 'user_id' => $user->id]);
    $task3 = Task::factory()->create(['priority' => 3, 'user_id' => $user->id]);

    
    $response = $this->get('/tasks');

    
    $response->assertStatus(200);

  
    $response->assertSeeInOrder([
        $task3->name,
        $task2->name,
        $task1->name,
    ]);
});
