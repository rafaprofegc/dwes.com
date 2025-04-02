@php
    use App\Util\Html;
    
    Html::inicio("Bienvenid@s alumn@s", ['css/formulario.css', 'css/general.css', 'css/tablas.css']);
@endphp
    <h1>Error</h1>

    <span class="negrita">Mensaje</span> : {{ $error['mensaje'] }}<br>
    <span class="negrita">Punto de recuperación</span>: <a href="{{ $error['enlace'] }}">{{ $error['texto'] }}</a>
@php
    Html::fin(['resources/js']);
@endphp