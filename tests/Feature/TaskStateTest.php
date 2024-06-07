<?php

use App\Models\Task;

it('Marca las tareas como terminadas', function () {
    $task = Task::factory()->create();

    $response = $this->post(route('tasks.complete', $task));

    $this->assertDatabaseHas('tasks', [
        'id' => $task->id,
        'completed' => true,
    ]);

    $response->assertRedirect(route('tasks.index'));
});
