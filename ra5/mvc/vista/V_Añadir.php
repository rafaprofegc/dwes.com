<?php
namespace mvc\vista;

use Exception;
use mvc\vista\Vista;
use util\seguridad\JWT;

class V_Añadir extends Vista {
    public function genera_salida(mixed $datos): void {
        // Si el cliente no ha abierto sesión,
        // formulario para abrir sesión

        // Si el cliente ha abierto sesión, sus datos
        // y el botón de cierre de sesión

        // Lista con los artículos en el carrito

        // Importe total del carrito

        // Botón para finalizar compra

        // Botón seguir comprando
        $this->inicio_html("Añadir al carrito", ["/estilos/general.css", "/estilos/tablas.css"]);

        if( isset($_SESSION['cliente']) ) {
            $cliente = $_SESSION['cliente'];
            echo "<h3>{$cliente->nombre} {$cliente->apellidos}</h3>";
            echo <<<CIERRA_SESION
                <form method="POST" action="/ra5/index.php">
                    <button type="submit" name="idp" id="idp" value="Cierra_sesion">Cerrar sesión</button>
                </form>
            CIERRA_SESION;
        }
        else {
            echo <<<FORM
                <form method="POST" action="{$_SERVER['PHP_SELF']}">
                    <!-- <input type="hidden" name="idp" id="idp" value="autenticar"> -->
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" size="30">

                    <label for="clave">Clave</label>
                    <input type="password" name="clave" id="clave" size="10">

                    <button type="submit" name="idp" id="idp1" value="autenticar">Inicia sesión</button>
                    <button type="submit" name="idp" id="idp2" value="registrar">Regístrese</button>
                </form>
            FORM;
        }

        // El carrito
        $carrito = $_SESSION['carrito'];
        echo <<<TABLA
        <table>
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Descuento</th>
                    <th>Precio Neto</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
        TABLA;
        $importe = 0;
        foreach($carrito as $articulo ) {
            echo "<tr>" . PHP_EOL;
            echo "<td>{$articulo->referencia}</td>" . PHP_EOL;
            echo "<td>{$articulo->descripcion}</td>" . PHP_EOL;
            echo "<td>{$articulo->pvp} €</td>" . PHP_EOL;
            $dto = $articulo->dto_venta * 100;
            echo "<td>{$dto} %</td>" . PHP_EOL;
            $precio_neto = $articulo->pvp - $articulo->pvp * $articulo->dto_venta;
            $importe += $precio_neto;
            echo "<td>$precio_neto €</td>" . PHP_EOL;
            echo "</tr>" . PHP_EOL;
        }
        echo <<<TABLA
            </tbody>
        </table>
        TABLA;

        echo "<h4>Importe total: $importe €</h4>";

        echo <<<FORM
            <form action="{$_SERVER['PHP_SELF']}" method="POST">
                <button type="submit" name="idp" id="idp1" value="finalizar_compra">Finalizar compra</button>
                <button type="submit" name="idp" id="idp2" value="seguir_comprando">Seguir comprando</button>
            </form>
        FORM;
        $this->fin_html();
    }
}
?>