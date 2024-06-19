<h1 style="color:red;">Tareas</h1>
<a href="/tasks/create" style="color:indigo;">Crear</a>
<form action="{{ route('tasks.index') }}" method="get">
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
        <a href="{{ $task->path() }}">{{ $task->name }}</a> ({{ $task->user->name }}) - Prioridad: 
        @switch($task->prioridad)
            @case(1)
                Baja
                @break
            @case(2)
                Media
                @break
            @case(3)
                Alta
                @break
            @default
                Sin prioridad
        @endswitch
    </li>
@endforeach
</ul>
