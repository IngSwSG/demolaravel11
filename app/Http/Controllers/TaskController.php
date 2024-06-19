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
            ->orderBy('prioridad', 'desc')
            ->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'search' => $search,
            'users' => User::all()
        ]);
    }


    function show(Task $task)
    {

        return view('tasks.show', [
            'task' => $task
        ]);
    }

    function create()
    {

        return view('tasks.create', [
            'users' => User::all()
        ]);
    }

    function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'prioridad' => 'required|integer|between:1,3',
        ]);
    
        Task::create([
            'name' => $validated['name'],
            'user_id' => $validated['user_id'],
            'prioridad' => $validated['prioridad'],
        ]);
    
        return redirect()->route('tasks.index');
    }

    function edit(Task $task)
    {

        return view('tasks.edit', [
            'task' => $task,
            'users' => User::all()
        ]);
    }

    function update(Task $task, Request $request)
    {

        $data = $request->validate([
            'name' => 'required',
            'user_id' => 'required'
        ]);

        $task->update($data);

        return redirect()->route('tasks.index');
    }

    function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index');
    }
}
