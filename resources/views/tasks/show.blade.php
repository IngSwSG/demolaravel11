<h1>Tarea # {{ $task->id }}</h1>
<div>
    {{ $task->name }}
    <p>{{ $task->created_at }}</p>
    <p>
        <small>Creado por: {{ $task->user->name }}</small>
    </p>

    <p>
        @if($task->completed)
           Estado: Completada
        @else
        Estado: Incompleta
        @endif
    </p>

    <a href="{{ route('tasks.edit', $task) }}">Editar</a>
    <form action="{{ route('tasks.destroy', $task) }}" method="post" style="display: inline;">
        @csrf
        @method('delete')
        <button type="submit">Eliminar</button>
    </form>
    
    <form action="{{ route('tasks.complete', $task->id) }}" method="post" style="display: inline;">
        @csrf
        @method('patch')
        <button type="submit">
            @if($task->completed)
                Desmarcar como Completada
            @else
                Marcar como Completada
            @endif
        </button>
    </form>
   
</div>

