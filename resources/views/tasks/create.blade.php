<h1>Creado una tarea</h1>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('tasks.store') }}" method="post">
    @csrf
    <div>
        <label for="name">Nombre</label>
        <input type="text" name="name" id="name">
        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label for="user_id">Usuario</label>
        <select name="user_id" id="user_id">
            <option value=""></option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label for="priority">Prioridad:</label>
        <select id="priority" name="priority">
            <option value="3">3</option>
            <option value="2">2</option>
            <option value="1">1</option>
        </select>
        @error('priority')
            <div>{{ $message }}</div>
        @enderror
    </div>
    <button type="submit">Crear tarea</button>
</form>