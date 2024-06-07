<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #343a40;
        }
        a.create-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 15px;
            color: #fff;
            background-color: #007bff;
            border-radius: 5px;
            text-decoration: none;
        }
        a.create-button:hover {
            background-color: #0056b3;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], select {
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        button {
            padding: 10px 15px;
            color: #fff;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #fff;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }
        span.completed {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <h1>Tareas</h1>
    <a href="/tasks/create" class="create-button">Crear</a>
    <form action="{{ route('tasks.index') }}">
        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar tareas">
        <select name="user_id" id="user_id">
            <option value="">Todos los usuarios</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $user->id == request('user_id') ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
        <button type="submit">Buscar</button>
    </form>
    <ul>
    @foreach ($tasks as $task)
        <li>
            <a href="{{ $task->path() }}">{{ $task->name }}</a> ({{ $task->user->name }})
            @if (!$task->completed)
                <form action="{{ route('tasks.complete', $task) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Marcar como completada</button>
                </form>
            @else
                <span class="completed">Completada</span>
            @endif
        </li>
    @endforeach
    </ul>
</body>
</html>


