{!! App\Util\Html::start("Error", ['css/global.css', 'css/table.css', 'css/form.css']) !!}


<h1>Error</h1>
<p>Ha ocurrido un problema.</p>

@if(isset($error))
    <p style="color: red;">{{ $error }}</p>
@endif

<a href="{{ route('categoria.index') }}">Volver a la lista de categorías</a>


{!! App\Util\Html::end() !!}
