{!! App\Util\Html::start("Categoría eliminada", ['css/global.css', 'css/table.css', 'css/form.css']) !!}


<h1>Categoría Eliminada</h1>

<p>La categoría con ID <strong>{{ $categoria->id_categoria }}</strong> ha sido eliminada.</p>

<a href="{{ route('categoria.index') }}">Volver a la lista</a>


{!! App\Util\Html::end() !!}
