<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'Complete' => 'boolean', // Agrega esta línea
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function path()
    {
        return '/tasks/'.$this->id;

    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $user_id = $request->input('user_id');
        $priority = $request->input('priority');

        // Construir la consulta base de tareas
        $tasksQuery = Task::query();

        // Aplicar filtros
        if (! empty($search)) {
            $tasksQuery->where('name', 'like', '%'.$search.'%');
        }

        if (! empty($user_id)) {
            $tasksQuery->where('user_id', $user_id);
        }

        if (! empty($priority)) {
            $tasksQuery->where('priority', $priority);
        }

        // Aplicar ordenamiento por prioridad utilizando el método ordenarPrioridad
        $tasksQuery->ordenarPrioridad(); // Llamada al método definido en el controlador

        // Obtener las tareas resultantes
        $tasks = $tasksQuery->get();

        // Obtener usuarios si es necesario
        $users = User::all(); // Ajusta según cómo obtienes y pasas los usuarios a la vista

        // Retornar la vista con los datos
        return view('tasks.index', compact('tasks', 'users', 'search'));
    }
}
