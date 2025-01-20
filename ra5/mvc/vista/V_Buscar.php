<?php
namespace mvc\vista;

use mvc\vista\Vista;

class V_Buscar extends Vista {

    public function genera_salida(mixed $datos): void {
        $this->inicio_html("Resultados de la búsqueda", ["/estilos/general.css", "/estilos/tablas.css"]);

        //Datos del usuario
        if( isset($_SESSION['cliente'])) {
            $cliente = $_SESSION['cliente'];
            echo "<h3>{$cliente->nombre} {$cliente->apellidos}</h3>";
            echo <<<CIERRA_SESION
                <form method="POST" action="/ra5/index.php">
                    <button type="submit" name="idp" id="idp" value="cerrar_sesion">Cerrar sesión</button>
                </form>
            CIERRA_SESION;
        }
        else {
            echo <<<FORM
            <form method="POST" action="/ra5/index.php">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" size=30 required>

                <label for="clave">Clave</label>
                <input type="password" name="clave" id="clave" size=10 required>

                <button type="submit" name="idp" id="idp" value="autenticar">Abrir sesión</button>
            </form> 
            FORM;
        }

        // Nº de artículos en el carrito
        if( isset($_SESSION['carrito']) ) {
            $en_carrito = count($_SESSION['carrito']);
            echo "<h3>Artículos en el carrito: $en_carrito</h3>";
        }

        // Formulario de búsqueda
        echo <<<FORM
        <form method="POST" action="/ra5/index.php">
            <input type="text" name="descripcion" id="descripcion" size="40">
            <button type="submit" name="idp" id="idp" value="buscar">Buscar artículos</button>
        </form>
        FORM;

        echo <<<TABLA
        <h3>Artículos encontrados</h3>
        <table>
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Descripción</th>
                    <th>PVP</th>
                    <th>Dto</th>
                    <th>Categoria</th>
                    <th>Disponible?</th>
                    <th>IVA</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
        TABLA;

        // Artículos encontrados
        foreach($datos as $articulo) {
            echo "<tr>" . PHP_EOL;
            echo "<td>{$articulo->referencia}</td>" . PHP_EOL;
            echo "<td>{$articulo->descripcion}</td>" . PHP_EOL;
            echo "<td>{$articulo->pvp}</td>" . PHP_EOL;
            echo "<td>" . (floatval($articulo->dto_venta) * 100 ) . "%</td>" . PHP_EOL;
            echo "<td>{$articulo->categoria}</td>" . PHP_EOL;
            if( $articulo->und_disponibles > 0 ) {
                echo "<td>Entrega inmediata</td>" . PHP_EOL;
            }
            else {
                if( $articulo->fecha_disponible ) {
                    $disponible = $articulo->fecha_disponible->format(self::FORMATO_FECHA);
                }
                echo "<td>Agotado (estimado $disponible)</td>" . PHP_EOL;
            }
            echo "<td>{$articulo->tipo_iva}</td>" . PHP_EOL;
            echo <<<FORM
                <td><form method="POST" action="/ra5/index.php">
                    <input type="hidden" name="referencia" id="referencia" value="{$articulo->referencia}">
                    <button type="submit" name="idp" id="idp" value="añadir">Añadir</button>
                </form></td>
            FORM;
            echo "</tr>" . PHP_EOL;
        }

        $this->fin_html();
    }
}
?>