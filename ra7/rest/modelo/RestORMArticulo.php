<?php
namespace rest\modelo;

use rest\modelo\RestORMBase;
use rest\entidad\Articulo;

class RestORMArticulo extends RestORMBase {
    protected string $tabla = "articulo";
    protected string $clave_primaria = "referencia";

    public function getClaseEntidad(): string {
        return Articulo::class;
    }

    public function validarDatos(bool $nuevo = true): Articulo {
        
        $cuerpo = file_get_contents("php://input");
        $datos = json_decode($cuerpo, true);

        // $datos['descripcion'] = 'Nueva descripción', $datos['pvp'] = 11.5;
        
        // Sanear
        $filtro_saneamiento = ['referencia' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'descripcion' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'pvp' => ['filter' => FILTER_SANITIZE_NUMBER_FLOAT, 'flags' => FILTER_FLAG_ALLOW_FRACTION],
                               'dto_venta' => ['filter' => FILTER_SANITIZE_NUMBER_FLOAT, 'flags' => FILTER_FLAG_ALLOW_FRACTION],
                               'und_vendidas' => ['filter' => FILTER_SANITIZE_NUMBER_FLOAT, 'flags' => FILTER_FLAG_ALLOW_FRACTION],
                               'und_disponibles' => ['filter' => FILTER_SANITIZE_NUMBER_FLOAT, 'flags' => FILTER_FLAG_ALLOW_FRACTION],
                               'fecha_disponible' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'categoria' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'tipo_iva' => FILTER_SANITIZE_SPECIAL_CHARS];
        $datos_saneados = filter_var_array($datos, $filtro_saneamiento, true);
        
        $filtro_validación = ['referencia' => FILTER_DEFAULT,
                              'descripcion' => FILTER_DEFAULT,
                              'pvp' => ['filter' => FILTER_VALIDATE_FLOAT,
                                        'options' => ['min_range' => 0],
                                        'flags' => FILTER_NULL_ON_FAILURE],
                              'dto_venta' => ['filter' => FILTER_VALIDATE_FLOAT,
                                              'options' => ['min_range' => 0, 'max_range' => 0.5],
                                              'flags' => FILTER_NULL_ON_FAILURE],
                              'und_vendidas' => ['filter' => FILTER_VALIDATE_FLOAT,
                                                 'options' => ['min_range' => 0],
                                                 'flags' => FILTER_NULL_ON_FAILURE],
                              'und_disponibles' => ['filter' => FILTER_VALIDATE_FLOAT,
                                                    'options' => ['min_range' => 0],
                                                    'flags' => FILTER_NULL_ON_FAILURE],
                              'fecha_disponible' => FILTER_DEFAULT,
                              'categoria' => FILTER_DEFAULT,
                              'tipo_iva' => FILTER_DEFAULT];
        
        $datos_validados = filter_var_array($datos_saneados, $filtro_validación);
        
        if( $nuevo ) {
            $articulo = new Articulo($datos_validados);
            return $articulo;
        }
        else {
            $articulo = new Articulo(array_filter($datos_validados));
            return $articulo;
        }
    }

    public function getFiltro(): array {
        $filtro_saneamiento = ['referencia' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'descripcion' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'categoria' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'tipo_iva' => FILTER_SANITIZE_SPECIAL_CHARS
                                ];

        $datos_filtrados = filter_input_array(INPUT_GET, $filtro_saneamiento, false);
        if( $datos_filtrados ) return $datos_filtrados;
        else return null;
    }
}
?>