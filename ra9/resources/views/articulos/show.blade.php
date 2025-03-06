@php
use App\Util\Html;

Html::inicio("Datos del artículo", ['estilos/general.css', 'estilos/tablas.css']);
@endphp
<h2>Datos del artículo</h2>
<table>
    <tbody>
        <tr><th>Referencia</th><td>{{$articulo->referencia}}</td></tr>
        <tr><th>Descripción</th><td>{{$articulo->descripcion}}</td></tr>
        <tr><th>PVP</th><td>{{$articulo->pvp}}€</td></tr>
        <tr><th>Descuento</th><td>{{$articulo->dto_venta * 100}}%</td></tr>
        <tr><th>Und.Vendidas</th><td>{{$articulo->und_vendidas}}</td></tr>
        <tr><th>Und.Disponibles</th><td>{{$articulo->und_disponibles}}</td></tr>
        <tr><th>Fecha disponible</th><td>{{$articulo->fecha_disponible}}</td></tr>
        <tr><th>Categoría</th><td>{{$articulo->categoria}}</td></tr>
        <tr><th>Tipo IVA</th><td>{{$articulo->tipo_iva}}</td></tr>
    </tbody>
</table>
<p><a href="/articulos">Volver a la lista de artículos</a></p>

@php
Html::fin()
@endphp