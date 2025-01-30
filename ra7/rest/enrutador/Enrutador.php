<?php
namespace rest\enrutador;

use rest\enrutador\Ruta;

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
        $this->rutas[] = new Ruta("GET", "/articulos", rest\modelo\ORMArticulo::class, "getAll");
        $this->rutas[] = new Ruta("GET", "/articulos/")

    }
}