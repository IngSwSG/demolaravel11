<h1>Tarea # {{ $task->id }}</h1>
    <div>
        {{ $task->name }}
        <p>{{ $task->created_at }}</p>
        <p>
            <small>Creado por: {{ $task->user->name }}</small>
        </p>

        <a href="{{ route('tasks.edit', $task) }}">Editar</a>
        <form action="{{ route('tasks.destroy', $task) }}" method="post">
            @csrf
            @method('delete')
            <button type="submit">Eliminar</button>
        </form>

        @if (!$task->completed)
            <form action="{{ route('tasks.complete', $task) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit">Marcar como Completada</button>
            </form>
        @else
            <p>Esta tarea ya está completada.</p>
        @endif
    </div>