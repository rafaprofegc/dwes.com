<?php
namespace mvc\vista;

class V_Articulos extends Vista {
    public function genera_salida(mixed $datos): void {

        $this->inicio_html("Listado de artículos", ["/estilos/general.css", "/estilos/tablas.css"]);

        echo <<<TABLA
        <table>
            <thead>
                <tr>
                    <th>Referencia</th>
                    <th>Descripción</th>
                    <th>PVP</th>
                    <th>Dto</th>
                    <th>Und.Vend.</th>
                    <th>Und.Disp.</th>
                    <th>Fecha Disp.</th>
                    <th>Categoría</th>
                    <th>Tipo IVA</th>
                </tr>
            </thead>
            <tbody>
        TABLA;
        foreach($datos as $articulo ) {
            echo "<tr>" . PHP_EOL;
            echo "<td>{$articulo->referencia}</td>" . PHP_EOL;
            echo "<td>{$articulo->descripcion}</td>" . PHP_EOL;
            echo "<td>{$articulo->pvp} €</td>" . PHP_EOL;
            echo "<td>" . $articulo->dto_venta * 100 . " %</td>" . PHP_EOL;
            echo "<td>{$articulo->und_vendidas}</td>" . PHP_EOL;
            echo "<td>{$articulo->und_disponibles}</td>" . PHP_EOL;
            $fecha_disponible = $articulo->fecha_disponible ?
                                $articulo->fecha_disponible->format('d/m/Y') :
                                "";
            echo "<td>$fecha_disponible</td>" . PHP_EOL;
            echo "<td>{$articulo->categoria}</td>" .  PHP_EOL;
            $tipos_iva = ['N' => 'Normal', 'R' => 'Reducido', "SR" => 'Superreducido'];
            echo "<td>{$tipos_iva[$articulo->tipo_iva]}</td>" . PHP_EOL;
            echo "</tr>" . PHP_EOL;

        }
        $this->fin_html();

    }

}
?>