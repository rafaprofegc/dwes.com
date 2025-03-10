{{ App\Util\Html::inicio("Nuevo artículo", ['estilos/general.css', 'estilos/formulario.css']) }}
<h2>{{$titulo}}</h2>
@if( $errors->any() )
    <ul>
        @foreach( $errors->all() as $error ) 
        <li>{{$error}}</li>
        @endforeach
    </ul>
@endif
@if( $op_crud == "create")
    <form method="POST" action="/articulos">
@else
<form method="POST" action="/articulos/{{$articulo->referencia}}">
    @method('PUT')
@endif
    @csrf
    <fieldset>
        <label for="referencia">Referencia</label>
        <input type="text" name="referencia" id="referencia" required size="15"
        {!! $op_crud == 'edit' ? "value='$articulo->referencia'" : ""!!}>

        <label for="descripcion">Descripción</label>
        <input type="text" name="descripcion" id="descripcion" required size="40"
        {!! $op_crud == 'edit' ? "value='$articulo->descripcion'" : ""!!}>

        <label for="pvp">PVP</label>
        <input type="text" name="pvp" id="pvp" required size="5"
        {!! $op_crud == 'edit' ? "value='$articulo->pvp'" : ""!!}>

        <label for="dto_venta">Descuento</label>
        <input type="text" name="dto_venta" id="dto_venta" size="5"
        {!! $op_crud == 'edit' ? "value='$articulo->dto_venta'" : ""!!}>

        @if( $op_crud == 'edit')
        <label for="und_vendidas">Und. Vendidas</label>
        <input type="number" name="und_vendidas" id="und_vendidas" size="5"
        {!! $op_crud == 'edit' ? "value='$articulo->und_vendidas'" : ""!!}>
        @else
        <input type="hidden" name="und_vendidas" id="und_vendidas" value="0">
        @endif

        <label for="und_disponibles">Und. Disponibles</label>
        <input type="number" name="und_disponibles" id="und_disponibles" size="5">

        <label for="fecha_disponible">Fecha disponible</label>
        <input type="date" name="fecha_disponible" id="fecha_disponible">

        <label for="categoria">Categoría</label>
        <select name="categoria" id="categoria" size="1">
            @foreach($categorias as $categoria)
                <option value="{{$categoria->id_categoria}}">{{$categoria->descripcion}}</option>
            @endforeach
        </select>

        <label for="tipo_iva">Tipo IVA</label>
        <select name="tipo_iva" id="tipo_iva" size="1">
            <option value="N">Normal</option>
            <option value="R">Reducido</option>
            <option value="SR">Superreducido</option>
        </select>
    </fieldset>
    <input type="submit" name="operacion" id="operacion" value="Crear artículo">
</form>
{{ App\Util\Html::fin() }}