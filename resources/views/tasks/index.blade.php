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
@foreach($tasks as $task)
    <div>
        <h3>{{ $task->name }}</h3>
        <p>Assigned to: {{ $task->user->name }}</p>
        @if (!$task->completed)
            <form action="{{ route('tasks.complete', $task) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit">Mark as Completed</button>
            </form>
        @else
            <p>Completed</p>
        @endif
    </div>
@endforeach
