<h1>Tareas</h1>
<a href="/tasks/create">Crear</a>
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
    <p>{{ $task->name }} - {{ $task->completed ? 'Completed' : 'Pending' }}</p>
    @if (!$task->completed)
        <form action="{{ route('tasks.complete', $task) }}" method="POST">
            @csrf
            @method('PUT')
            <button type="submit">Mark as Completed</button>
        </form>
    @endif
@endforeach

<h1>Tasks</h1>
    @if(session('status'))
        <p style="color:green;">{{ session('status') }}</p>
    @endif
    <ul>
        @foreach ($tasks as $task)
            <li>
                {{ $task->name }} - {{ $task->completed ? 'Completed' : 'Pending' }}
                @if (!$task->completed)
                    <form action="{{ route('tasks.complete', $task) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit">Mark as Completed</button>
                    </form>
                @endif
            </li>
        @endforeach
    </ul>