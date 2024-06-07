<h1>Tarea # {{ $task->id }}</h1>
<div>
    {{ $task->name }}
    <p>{{ $task->created_at }}</p>
    <p>
        <small>Creado por: {{ $task->user->name }}</small>
    </p>

    <a href="{{ route('tasks.edit', $task) }}">Editar</a>

    @if ($task->completed)
        <p>Estado: Terminada</p>
    @else
        <p>Estado: Pendiente</p>
    @endif

    <a href="{{ route('tasks.edit', $task) }}">Editar</a>

    @if (!$task->completed)
        <form action="{{ route('tasks.complete', $task) }}" method="post">
            <button type="submit">Marcar como Terminada</button>
            @csrf
        </form>
    @endif
    @csrf


    <form action="{{ route('tasks.destroy', $task) }}" method="post">
        @csrf
        @method('delete')
        <button type="submit">Eliminar</button>
    </form>
</div>