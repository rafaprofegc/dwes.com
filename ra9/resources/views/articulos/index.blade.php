{!! App\Util\Html::inicio("Listado de artículos", ['estilos/general.css', 'estilos/tablas.css']); !!}
<!-- Verificar si viene de la creación de un artículo -->
@if( session('resultado') )
<p class='negrita'>{{session('resultado')}}</p>
@endif
<h2>Listado de artículos</h2>
<p>Artículos en la BBDD: {{ $numero }}</p>
<table border="1">
    <thead>
        <tr>
            <th>Referencia</th>
            <th>Descripción</th>
            <th>PVP</th>
            <th>Descuento</th>
            <th>Und. vendidas</th>
            <th>Und. disponibles</th>
            <th>Fecha disponible</th>
            <th>Categoría</th>
            <th>Tipo IVA</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach( $articulos as $articulo)
            <tr>
                <td>{{ $articulo->referencia }}</td>
                <td>{{ $articulo->descripcion}}</td>
                <td>{{ $articulo->pvp}} €</td>
                <td>{{ $articulo->dto_venta * 100}}</td>
                <td>{{ $articulo->und_vendidas}}</td>
                <td>{{ $articulo->und_disponibles}}</td>
                <td>{{ $articulo->fecha_disponible}}</td>           
                <td>{{ $articulo->categoria}}</td>
                <td>{{ $articulo->tipo_iva}}</td>
                <td><a href="/articulos/{{$articulo->referencia}}">Ver</a></td>
                <td><a href="/articulos/{{$articulo->referencia}}/edit">Actualizar</a></td>
            </tr>
        @endforeach
    </tbody>
</table>
<p>Para crear un nuevo artículo hacer clic <a href="/articulos/create">aquí</a></p>
{!! App\Util\Html::fin(); !!}