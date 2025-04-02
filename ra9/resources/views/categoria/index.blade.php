{!! App\Util\Html::start("Lista categorías", ['/css/global.css', '/css/table.css', '/css/form.css']) !!}

<h1>Lista de Categorías</h1>
<a href="{{ route('categoria.create') }}">Crear Nueva Categoría</a>

<table class="table">
    <thead>
        <tr>
            <th>Descripción</th>
            <th>ID</th>
            <th>Categoria Padre</th>
            <th>Subcategorías</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categorias as $categoria)
        <tr>
            <td><strong>{{ $categoria->descripcion }}</strong></td>
            <td>{{ $categoria->id_categoria }}</td>
            <td>
                @if ($categoria->categoria_padre)
                    {{ $categoria->categoria_padre }}
                @else
                    Ninguna
                @endif
            </td>
            <td>
                @if ($categoria->children->isNotEmpty())
                    <ul>
                        @foreach ($categoria->children as $child)
                            <li>{{ $child->descripcion }} (ID: {{ $child->id_categoria }})</li>
                        @endforeach
                    </ul>
                @else
                    No tiene subcategorías.
                @endif
            </td>
            <td>
                <a href="{{ route('categoria.show', $categoria->id_categoria) }}">Ver detalles</a> |
                <a href="{{ route('categoria.edit', $categoria->id_categoria) }}">Editar</a> |
                <form action="{{ route('categoria.destroy', $categoria->id_categoria) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{!! App\Util\Html::end() !!}
