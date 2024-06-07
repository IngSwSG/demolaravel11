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
        <p>completada?: {{ $task->isCompleted ? 'True' : 'False' }}</p>

        <input type="hidden" name="isCompleted" value="0"> 

        <input type="checkbox" name="isCompleted" id="isCompleted" value="1"
            {{ $task->isCompleted ? 'checked' : '' }}> 
    </div>
    <button type="submit">Actualizar tarea</button>
</form>