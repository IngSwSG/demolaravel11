<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas</title>
</head>
<body>
    <h1>Tareas</h1>
    <a href="/tasks/create">Crear</a>
    <form action="{{ route('tasks.index') }}" method="GET">
        <input type="text" name="search" value="{{ $search }}">
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
                        <button type="submit" class="btn btn-success">Marcar como completada</button>
                    </form>
                @else
                    <span>Completada</span>
                @endif
            </li>
        @endforeach
    </ul>
</body>
</html>
