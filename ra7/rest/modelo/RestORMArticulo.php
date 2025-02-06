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

    public function validarDatos(): Articulo {
        
        $cuerpo = file_get_contents("php://input");
        $datos = json_decode($cuerpo, true);
        // Sanear
        $filtro_saneamiento = ['referencia' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'descripcion' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'pvp' => FILTER_SANITIZE_NUMBER_FLOAT,
                               'dto_venta' => FILTER_SANITIZE_NUMBER_FLOAT,
                               'und_vendidas' => FILTER_SANITIZE_NUMBER_FLOAT,
                               'und_disponibles' => FILTER_SANITIZE_NUMBER_FLOAT,
                               'fecha_disponible' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'categoria' => FILTER_SANITIZE_SPECIAL_CHARS,
                               'tipo_iva' => FILTER_SANITIZE_SPECIAL_CHARS];
        $datos_saneados = filter_var_array($datos, $filtro_saneamiento);
        
        $filtro_validación = ['referencia' => FILTER_DEFAULT,
                              'descripcion' => FILTER_DEFAULT,
                              'pvp' => ['filter' => FILTER_VALIDATE_FLOAT,
                                        'options' => ['min_range' => 0]],
                              'dto_venta' => ['filter' => FILTER_VALIDATE_FLOAT,
                                              'options' => ['min_range' => 0, 'max_range' => 0.5]],
                              'und_vendidas' => ['filter' => FILTER_VALIDATE_FLOAT,
                                                 'options' => ['min_range' => 0]],
                              'und_disponibles' => ['filter' => FILTER_VALIDATE_FLOAT,
                                                    'options' => ['min_range' => 0]],
                              'fecha_disponible' => FILTER_DEFAULT,
                              'categoria' => FILTER_DEFAULT,
                              'tipo_iva' => FILTER_DEFAULT];
        
        $datos_validados = filter_var_array($datos_saneados, $filtro_validación);
        
        $articulo = new Articulo($datos_validados);
        return $articulo;
    }
}
?>