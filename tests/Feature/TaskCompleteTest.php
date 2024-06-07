<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCompleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_task_can_be_marked_as_completed()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'completed' => false]);

        $response = $this->patch(route('tasks.complete', $task));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'completed' => true,
        ]);

        $response->assertRedirect(route('tasks.show', $task));
    }
}
