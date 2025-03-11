{{ \App\Util\Html::inicio("Error en la aplicación", ['estilos/general.css']) }}
<h2>Información del error</h2>
<p><span class="negrita">Mensaje</span>: {{ $error['mensaje'] }}<br>
    <span class="negrita">Punto de recuperación</span>: 
    <a href="{{$error['enlace']}}">{{$error['texto']}}</a>
</p>
{{ \App\Util\Html::fin() }}