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
        <label for="estado">Estado</label>
        <select name="estado" id="estado">
            <option value="0" {{ $task->estado === 0 ? 'selected' : '' }}>Incompleto</option>
            <option value="1" {{ $task->estado === 1 ? 'selected' : '' }}>Completado</option>
        </select>
    </div>
    <button type="submit">Actualizar tarea</button>
</form>
