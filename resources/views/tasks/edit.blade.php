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
        <label for="completed">Completado</label>
        <input type="hidden" name="completed" value="0"> <!-- Para asegurar que siempre se envíe un valor, incluso si el checkbox no está marcado -->
        <input type="checkbox" name="completed" id="completed" value="1" {{ $task->completed ? 'checked' : '' }}>
        <span>The completed field must be true or false.</span> <!-- Agregamos un mensaje de validación -->
    </div>
    <button type="submit">Actualizar tarea</button>
</form>
