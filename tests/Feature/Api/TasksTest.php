<?php

use App\Models\Task;

it('se obtiene la lista de tareas', function(){
    $tasks = Task::factory(3)->create();

    $this->get('/api/tasks')
        ->assertStatus(200)
        ->assertJsonCount(3, 'data')
        ->assertJson([
            'data' => $tasks->toArray(),
            'page' => 1
        ]);
});