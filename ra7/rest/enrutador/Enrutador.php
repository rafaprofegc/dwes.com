<?php
namespace rest\enrutador;

use rest\enrutador\Ruta;
use Exception;

use rest\modelo\RestORMArticulo;

class Enrutador {
    protected array $rutas;

    public function __construct() {
        $rutas = [];
        $this->iniciarRutas();
    }

    private function iniciarRutas(): void {
        /*
        Contemplamos las siguientes rutas:
        GET /articulos          -> Listado de todos los artículos
        GET /articulos/{ref}    -> Obtener un artículo concreto
        POST /articulos         -> Insertar un nuevo artículo
        PUT /articulos/{ref}    -> Actualizar un artículo
        DELETE /articulos/{ref} -> Elimina un artículo
        */
        $this->rutas[] = new Ruta("GET", "#^/articulos$#", RestORMArticulo::class, "getAll");
        $this->rutas[] = new Ruta("GET", "#^/articulos/(\w+)$#", RestORMArticulo::class, "get");
        $this->rutas[] = new Ruta("POST", "#^/articulos$#", RestORMArticulo::class, "insert");
        $this->rutas[] = new Ruta("PUT", "#^/articulos/(\w+)$#", RestORMArticulo::class, "update");
        $this->rutas[] = new Ruta("DELETE", "#^/articulos/(\w+)$#", RestORMArticulo::class, "delete");
    }

    public function despacha(): void {
        try {
            // Obtener el verbo de la petición
            $verbo = $this->obtenerVerbo();

            // Obtener la path de la petición
            $ruta_peticion = $this->obtenerPath();

            // Con el verbo y el path busco la ruta en el array rutas
            $ruta = $this->buscaRuta($verbo, $ruta_peticion);

            // Si hay ruta, se instancia el modelo y se invoca el método
            $datos = $this->ejecutaRuta($ruta, $ruta_peticion);

            if( $datos['exito'] ) {
                header( $_SERVER['SERVER_PROTOCOL']. $datos['codigo']);
                header("Content: application/json");
                echo json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                exit(0);
            }
            else {
                $this->gestionaError($datos);
            }

        }
        catch( Exception $e ) {
            $this->gestionaError($e);
        }
    }

    private function obtenerVerbo(): string {
        // Solo puede ser: POST, GET, PUT, DELETE, PATCH

        $verbo = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_SPECIAL_CHARS);
        
        if( $verbo === "POST" ) {
            if( isset($_POST['_method']) ) {
                $verbo = strtoupper(filter_input(INPUT_POST, 
                                    '_method', FILTER_SANITIZE_SPECIAL_CHARS));
                if( !in_array($verbo, ['PUT', 'DELETE', 'PATCH'])) {
                    throw new Exception("Bad Request", 400);
                }
            }
        }

        return $verbo;
    }

    private function obtenerPath(): string {
        $url = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_SPECIAL_CHARS);
        $path_peticion = parse_url($url, PHP_URL_PATH);
        return $path_peticion;
    } 

    private function buscaRuta(string $verbo, string $ruta_peticion): Ruta {
        foreach( $this->rutas as $ruta ) {
            // Comparar para cada ruta, el path y el verbo
            if( $ruta->esIgual($verbo, $ruta_peticion) ) {
                return $ruta;
            }
        }

        // En este punto, no lo ha encontrado
        throw new Exception("Bad Request", 400);
    }

    private function ejecutaRuta(Ruta $ruta, string $path_peticion): mixed {
        // Obtenemos la clase modelo que atiende esta petición
        $clase_modelo = $ruta->getClase();
        $metodo = $ruta->getFuncion();

        //$parametros = $this->obtenerParametros($path_peticion);
        $parametros = $this->obtenerParametros($ruta->getPath(), $path_peticion);

        $objeto_modelo = new $clase_modelo();
        $datos = call_user_func_array([$objeto_modelo, $metodo], $parametros);

        /* Datos recibidos desde el método del modelo 
           Es un array con los siguientes elementos
           - exito          -> True o False
           - resultado      -> Los datos solicitados si los hubiera (null)
           - codigo         -> Código y frase de estado
           - error          -> Los datos del error (null)
        */

        return $datos;

    }

    /*
    private function obtenerParametros(string $path_peticion): array {
        // Supongamos /articulos/acin0001/reseñas/50
        
        $parametros = [];
        $componentes = explode("/", $path_peticion);

        // $componentes es ["", "articulos", "acin0001", "reseñas", "50"]
        for( $i = 2; $i < count($componentes); $i+=2 ) {
            $parametros[] = $componentes[$i];
        }
        
        return $parametros;
    }
    */
    private function obtenerParametros(string $path_exp_reg, string $path_peticion ): array {

        // Suponer path_exp_reg: #^/articulos/(\w+)/reseñas/(\w+)$#
        //                                      s1           s2
        // Supongamos path_peticion: /articulos/acin0001/reseñas/50

        if( preg_match($path_exp_reg, $path_peticion, $parametros) ) {
            // Al ejecutar preg_match sobre la url anterior
            // $parametros[0] = /articulos/acin0001/reseñas/50
            // $parametros[1] = acin0001
            // $parametros[2] = 50
            // Hay que devolver el array $parametros menos el primer elemento
            
            array_shift($parametros);
            return $parametros;
        }
        else {
            return [];
        }
    }

    private function gestionaError(mixed $error): void {
        if( $error instanceof Exception ) {
            header($_SERVER['SERVER_PROTOCOL'] . " " . $error->getCode() . " " . $error->getMessage() );
            header("Content-Type: application/json");

        }
        else {
            header($_SERVER['SERVER_PROTOCOL'] . " " . $error['codigo']);
        }
    }
}