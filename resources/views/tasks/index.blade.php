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
@foreach ($tasks as $task)
    <li><a href="{{ $task->path() }}">{{ $task->name }}</a> ({{ $task->user->name }})</li>
@endforeach
@foreach ($tasks as $task)
    <div>
        <p>{{ $task->name }}</p>
        <p>Assigned to: {{ $task->user->name }}</p>
        <form action="{{ route('tasks.complete', $task) }}" method="POST" style="display: inline;">
            @csrf
            @method('PATCH')
            @if (!$task->completed)
                <button type="submit">Mark as Completed</button>
            @else
                <span>Completed</span>
            @endif
        </form>
    </div>
@endforeach
