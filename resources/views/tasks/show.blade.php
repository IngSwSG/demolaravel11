<h1>Tarea # {{ $task->id }}</h1>
<div>
    <p>{{ $task->name }}</p>
    <p>{{ $task->created_at }}</p>
    <p><small>Creado por: {{ $task->user->name }}</small></p>

    @if (!$task->completed)
        <form action="{{ route('tasks.complete', $task) }}" method="POST" style="display:inline">
            @csrf
            @method('PATCH')
            <button type="submit">Marcar como Completada</button>
        </form>
    @else
        <span>Completada</span>
    @endif

    <a href="{{ route('tasks.edit', $task) }}">Editar</a>
    <form action="{{ route('tasks.destroy', $task) }}" method="post" style="display:inline">
        @csrf
        @method('delete')
        <button type="submit">Eliminar</button>
    </form>
</div>
