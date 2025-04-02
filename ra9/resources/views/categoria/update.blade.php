{!! App\Util\Html::start("Categoría actualizada", ['css/global.css', 'css/table.css', 'css/form.css']) !!}


<h1>Categoría Actualizada</h1>

<p><strong>ID:</strong> {{ $categoria->id_categoria }}</p>
<p><strong>Descripción:</strong> {{ $categoria->descripcion }}</p>

<a href="{{ route('categoria.index') }}">Volver a la lista</a>


{!! App\Util\Html::end() !!}
