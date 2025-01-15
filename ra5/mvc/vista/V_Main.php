<?php
namespace mvc\vista;


class V_Main extends Vista {
    public function genera_salida(mixed $datos): void {
        $this->inicio_html("Página de inicio", ["/estilos/general.css", "/estilos/tablas.css"]);
        if( !isset($_COOKIE['jwt'])) {
?>
        <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
            <!-- <input type="hidden" name="idp" id="idp" value="autenticar"> -->
            <label for="email">Email</label>
            <input type="text" name="email" id="email" size="30">

            <label for="clave">Clave</label>
            <input type="password" name="clave" id="clave" size="10">

            <button type="submit" name="idp" id="idp1" value="autenticar">Inicia sesión</button>
            <button type="submit" name="idp" id="idp2" value="registrar">Regístrese</button>
        </form>
<?php
        }
?>
        <h3>Comience su compra buscando lo que quiera</h3>
        <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
            <input type="text" name="descripcion" id="descripcion" size="40">
            <button type="submit" name="idp" value="buscar">Buscar artículos</button>
        </form>

        <hr>
        <h3>Nuestros artículos en oferta hoy</h3>
<?php
        echo <<<TABLA
        <table>
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Descripción</th>
                    <th>PVP</th>
                    <th>Dto</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
        TABLA;
        foreach( $datos as $articulo_oferta ) {
            echo "<tr>" . PHP_EOL;
            echo "<td>{$articulo_oferta['referencia']}</td>" . PHP_EOL;
            echo "<td>{$articulo_oferta['descripcion']}</td>" . PHP_EOL;
            echo "<td>{$articulo_oferta['pvp']}</td>" . PHP_EOL;
            echo "<td>" . (floatval($articulo_oferta['dto_venta']) * 100) ."%</td>" . PHP_EOL;
            echo "<td>";
            echo "<form method='POST' action='{$_SERVER['PHP_SELF']}'>";
            echo "<input type='hidden' name='referencia' id='referencia' value='{$articulo_oferta['referencia']}'>";
            echo "<button type='submit' name='idp' value='añadir'>Añadir</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>" . PHP_EOL;
        }
        echo <<<TABLA
            </tbody>
        </table>
        TABLA;

        $this->fin_html();
    }
}

?>