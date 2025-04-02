{!! App\Util\Html::start("Edición de la categoría", ['css/global.css', 'css/table.css', 'css/form.css']) !!}

<h1>Editar Categoría</h1>
<form action="{{ route('categoria.update', $categoria->id_categoria) }}" method="POST">
    @csrf
    @method('PUT')
    
    <label>Descripción:</label>
    <input type="text" name="descripcion" value="{{ $categoria->descripcion }}" required>
    
    <label>Categoría Padre:</label>
    <select name="categoria_padre">
        <option value="">Ninguna</option>
        @foreach ($categorias as $cat)
            <option value="{{ $cat->id_categoria }}" @if($cat->id_categoria == $categoria->categoria_padre) selected @endif>
                {{ $cat->descripcion }}
            </option>
        @endforeach
    </select>

    <button type="submit">Actualizar</button>
</form>


{!! App\Util\Html::end() !!}
