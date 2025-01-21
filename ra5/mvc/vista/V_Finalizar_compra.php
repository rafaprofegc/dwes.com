<?php
namespace mvc\vista;

use mvc\vista\Vista;

class V_Finalizar_compra extends Vista {

    public function genera_salida(mixed $datos): void {
        $this->inicio_html("Finalizar la compra", ["/estilos/general.css", "/estilos/tablas.css"]);
        // 1º Presentar los datos del cliente
        if( isset($_SESSION['cliente']) ) {
            $cliente = $_SESSION['cliente'];
            echo "<h3>{$cliente->nombre} {$cliente->apellidos}</h3>";
        

            // 2º Presentar el carrito
            if( isset($_SESSION['carrito']) ) {
                echo <<<TABLA
                    <table>
                        <thead>
                            <tr>
                                <th>Referencia</th>
                                <th>Descripción</th>
                                <th>PVP</th>
                                <th>Dto</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                TABLA;

                $total_carrito = 0;
                foreach( $_SESSION['carrito'] as $articulo ) {
                    echo "<tr>" . PHP_EOL;
                    echo "<td>{$articulo->referencia}</td>" . PHP_EOL;
                    echo "<td>{$articulo->descripcion}</td>" . PHP_EOL;
                    echo "<td>{$articulo->pvp} €</td>" . PHP_EOL;
                    $dto = $articulo->dto_venta * 100;
                    echo "<td>{$dto} %</td>" . PHP_EOL;
                    $precio_neto = $articulo->pvp - $articulo->pvp * $articulo->dto_venta;
                    $total_carrito += $precio_neto;
                    echo "<td>$precio_neto €</td>" . PHP_EOL;
                    echo "</tr>" . PHP_EOL;
                }
                echo <<<TABLA
                    </tbody>
                </table>
                TABLA;
                echo "<h4>Importe del carrito: $total_carrito €</h4>";
            }

            // 3º Presentar el formulario para
            //    - Seleccionar una dirección de envío
            //    - Seleccionar una forma de envío

            echo <<<FORM
            <form method="POST" action="{$_SERVER['PHP_SELF']}">
                <label for="direccion_envio">Dirección envío</label>
                <select name="direccion_envio" id="direccion_envio" size="1">
            FORM;
            foreach( $datos['direcciones_envio'] as $de) {
                echo "<option value='{$de->id_dir_env}'>{$de->direccion} {$de->poblacion} {$de->provincia}</option>" . PHP_EOL;
            }
            echo <<<FORM
                </select>
                <label for="forma_envio">Forma de envío</label>
                <select name="forma_envio" id="forma_envio" size="1">
            FORM;
            foreach( $datos['formas_envio'] as $fe) {
                echo "<option value='{$fe->id_fe}'>{$fe->descripcion} - {$fe->coste}€</option>" . PHP_EOL;
            }
            echo <<<FORM
                </select>
                <button type="submit" name="idp" id="idp" value="crear_pedido">Finalizar pedido</button>
            </form>
            FORM;
        }
        else {
            require($_SERVER['DOCUMENT_ROOT'] . "/ra5/mvc/form_autenticar.php");
        }
        $this->fin_html();
    }
}