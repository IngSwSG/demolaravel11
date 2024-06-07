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




