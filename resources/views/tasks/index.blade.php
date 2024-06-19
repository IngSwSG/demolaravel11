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

<h2>Tareas Pendientes</h2>
<ul>
    @foreach ($tasks->where('completed', false) as $task)
        <li>
            <form action="{{ route('tasks.complete', $task) }}" method="POST" style="display: inline;">
                @csrf
                @method('PUT')
                <button type="submit">Completar</button>
            </form>
            <a href="{{ $task->path() }}">{{ $task->name }}</a> ({{ $task->user->name }}) Prioridad: {{ $task->priority }}
        </li>
    @endforeach
</ul>

<h2>Tareas Completadas</h2>
<ul>
    @foreach ($tasks->where('completed', true) as $task)
        <li>
            <form action="{{ route('tasks.incomplete', $task) }}" method="POST" style="display: inline;">
                @csrf
                @method('PUT')
                <button type="submit">Marcar como incompleta</button>
            </form>
            <a href="{{ $task->path() }}">{{ $task->name }}</a> ({{ $task->user->name }}) Prioridad: {{ $task->priority }}
        </li>
    @endforeach
</ul>