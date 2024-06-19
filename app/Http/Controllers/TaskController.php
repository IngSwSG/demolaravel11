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

        $tasks = Task::with('user')
            ->when($user_id, function ($query, $user_id) {
                return $query->where('user_id', $user_id);
            })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%");
            })
            ->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'search' => $search,
            'users' => User::all()
        ]);
    }

    public function show(Task $task)
    {
        return view('tasks.show', [
            'task' => $task
        ]);
    }

    public function create()
    {
        return view('tasks.create', [
            'users' => User::all()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'user_id' => 'required|exists:users,id',
            'priority' => 'required|integer|between:1,3'
        ]);

        Task::create($data);

        return redirect()->route('tasks.index');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', [
            'task' => $task,
            'users' => User::all()
        ]);
    }

    public function update(Task $task, Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'user_id' => 'required|exists:users,id',
            'priority' => 'required|integer|between:1,3'
        ]);

        $task->update($data);

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index');
    }
}
