<h1>Creando una tarea</h1>
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
        <label for="prioridad">Prioridad</label>
        <select name="prioridad" id="prioridad">
            <option value="1">1 - Baja</option>
            <option value="2">2 - Media</option>
            <option value="3">3 - Alta</option>
        </select>
    </div>
    <button type="submit">Crear tarea</button>
</form>
