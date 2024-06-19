<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user_id = $request->input('user_id');
        $order_by = $request->input('order_by');
        $tasksQuery = Task::query();

        if (! empty($search)) {
            $tasksQuery->where('name', 'like', '%'.$search.'%');
        }

        if (! empty($user_id)) {
            $tasksQuery->where('user_id', $user_id);
        }

        if (empty($order_by)) {
            $tasksQuery->orderBy('priority', 'desc');
        } else {
            switch ($order_by) {
                case 'created_at':
                    $tasksQuery->orderBy('created_at', 'desc');
                    break;
                case 'name':
                    $tasksQuery->orderBy('name', 'asc');
                    break;
                default:
                    break;
            }
        }

        $tasks = $tasksQuery->get();

        $users = User::all();

        return view('tasks.index', compact('tasks', 'users', 'search'));
    }

    public function show(Task $task)
    {

        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    public function create()
    {

        return view('tasks.create', [
            'users' => User::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'priority' => 'required|integer|in:1,2,3',
        ]);

        Task::create([
            'name' => $data['name'],
            'user_id' => $data['user_id'],
            'priority' => $data['priority'],
            'Complete' => false,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Tarea creada correctamente.');
    }

    public function edit(Task $task)
    {

        return view('tasks.edit', [
            'task' => $task,
            'users' => User::all(),
        ]);
    }

    public function update(Task $task, Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
            'user_id' => 'required',
        ]);

        $task->update($data);

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index');
    }

    public function complete(Task $task)
    {
        $task->Complete = true;
        $task->save();

        return redirect()->route('tasks.index');
    }
}
