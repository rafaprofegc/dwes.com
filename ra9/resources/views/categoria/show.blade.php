{!! App\Util\Html::start("Detalle de la categoría", ['css/global.css', 'css/table.css', 'css/form.css']) !!}


<h1>Detalles de la Categoría</h1>

<p><strong>ID:</strong> {{ $categoria->id_categoria }}</p>
<p><strong>Descripción:</strong> {{ $categoria->descripcion }}</p>

<p><strong>Categoría Padre:</strong> 
    @if ($categoria->parent)
        {{ $categoria->parent->descripcion }}
    @else
        Ninguna
    @endif
</p>

<a href="{{ route('categoria.index') }}">Volver a la lista</a>
<a href="{{ route('categoria.edit', $categoria->id_categoria) }}">Editar</a>


{!! App\Util\Html::end() !!}
