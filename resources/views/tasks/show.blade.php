<!-- tasks/show.blade.php -->
<h1>Tarea # {{ $task->id }}</h1>
<div>
    {{ $task->name }}
    <p>{{ $task->created_at }}</p>
    <p>
        <small>Creado por: {{ $task->user->name }}</small>
    </p>
    <p>
        Prioridad: {{ $task->priority }}
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
    
    <!-- Formulario para actualizar la prioridad -->
    <form action="{{ route('tasks.updatePriority', $task) }}" method="post" style="display: inline;">
        @csrf
        @method('patch')
        <label for="priority">Cambiar Prioridad:</label>
        <select name="priority" id="priority" onchange="this.form.submit()">
            <option value="1" {{ $task->priority == 1 ? 'selected' : '' }}>1</option>
            <option value="2" {{ $task->priority == 2 ? 'selected' : '' }}>2</option>
            <option value="3" {{ $task->priority == 3 ? 'selected' : '' }}>3</option>
        </select>
    </form>
</div>
