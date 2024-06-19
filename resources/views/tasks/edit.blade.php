<h1>Editando una tarea</h1>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('tasks.update', $task) }}" method="post">
    @csrf
    @method('put')
    <div>
        <label for="name">Nombre</label>
        <input type="text" name="name" id="name" value="{{ $task->name }}">
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label for="user_id">Usuario</label>
        <select name="user_id" id="user_id">
            <option value=""></option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ $user->id === $task->user_id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="priority">Prioridad</label>
        <select name="priority" id="priority">
            <option value="1" {{ $task->priority == 1 ? 'selected' : '' }}>1</option>
            <option value="2" {{ $task->priority == 2 ? 'selected' : '' }}>2</option>
            <option value="3" {{ $task->priority == 3 ? 'selected' : '' }}>3</option>
        </select>
    </div>

    <button type="submit">Actualizar tarea</button>
</form>