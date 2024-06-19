<h1 style="color:red;">Tareas</h1>
<a href="/tasks/create" style="color:indigo;">Crear</a>
<form action="{{ route('tasks.index') }}">
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
        <a href="{{ $task->path() }}">{{ $task->name }}</a> ({{ $task->user->name }}) - Prioridad: {{ $task->priority }}
        @if ($task->completed)
            <form action="{{ route('tasks.incomplete', $task) }}" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <button type="submit">Marcar como incompleta</button>
            </form>
        @else
            <form action="{{ route('tasks.complete', $task) }}" method="POST" style="display:inline;">
                @csrf
                @method('PUT')
                <button type="submit">Marcar como completada</button>
            </form>
        @endif
    </li>
@endforeach
</ul>

