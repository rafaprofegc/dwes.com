{!! App\Util\Html::start("Crea categoría", ['css/global.css', 'css/table.css', 'css/form.css']) !!}


<h1>Crear Nueva Categoría</h1>
<form action="{{ route('categoria.store') }}" method="POST">
    @csrf
    <label>ID Categoría:</label>
    <input type="text" name="id_categoria" required>
    
    <label>Descripción:</label>
    <input type="text" name="descripcion" required>
    
    <label>Categoría Padre:</label>
    <select name="categoria_padre">
        <option value="">Ninguna</option>
        @foreach ($categorias as $categoria)
            <option value="{{ $categoria->id_categoria }}">{{ $categoria->descripcion }}</option>
        @endforeach
    </select>

    <button type="submit">Guardar</button>
</form>
<a href="{{ route('categoria.index') }}">Volver a la lista</a>

{!! App\Util\Html::end() !!}