<?php
namespace mvc\vista;

use mvc\vista\Vista;
use DateTime;

class V_Autenticar extends Vista {

    public function genera_salida(mixed $datos): void {
        $this->inicio_html("Inicio de la compra del usuario", ["/estilos/general.css", "/estilos/tablas.css"]);
        // Nombre y apellidos del usuario
        $cliente = $_SESSION['cliente'];
        echo "<h3>Hola, {$cliente->nombre} {$cliente->apellidos}</h3>";

        // Botón de cierre de sesión
?>
        <form method="POST" action="/ra5/index.php">
            <button type="submit" name="idp" id="idp" value="cerrar_sesion">Cerrar sesión</button>
        </form>
<?php

        // Los últimos envíos
        echo <<<TABLA
            <h3>Últimos envíos</h3>
            <table>
                <thead>
                    <tr>
                        <th>Nº Envío</th>
                        <th>Fecha</th>
                        <th>Referencia</th>
                        <th>Descripción</th>
                        <th>Unidades</th>
                        <th>Precio</th>
                        <th>Dto</th>
                    </tr>
                </thead>
                <tbody> 
            TABLA;

            foreach($datos as $fila) {
                echo "<tr>" . PHP_EOL;
                echo "<td>{$fila['nenvio']}.</td>" . PHP_EOL;
                $fecha = new DateTime($fila['fecha']);
                echo "<td>" . $fecha->format('d/m/Y - H:i:s') . "</td>" . PHP_EOL;
                echo "<td>{$fila['referencia']}.</td>" . PHP_EOL;
                echo "<td>{$fila['descripcion']}.</td>" . PHP_EOL;
                echo "<td>{$fila['unidades']}.</td>" . PHP_EOL;
                echo "<td>{$fila['precio']}.</td>" . PHP_EOL;
                echo "<td>" . (floatval($fila['dto']) * 100) . "%</td>" . PHP_EOL;
                echo "</tr>" . PHP_EOL;
            }

        // Formulario de búsqueda de artículos
?>
        <form method="POST" action="/ra5/index.php">
            <label for="descripcion">Descripción Artículo</label>
            <input type="text" name="descripcion" id="descripcion" size="40">

            <button type="submit" name="idp" id="idp" value="buscar">Buscar</button>
        </form>
<?php
        $this->fin_html();
    }
}
?>