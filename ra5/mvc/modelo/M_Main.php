<?php
namespace mvc\modelo;

use mvc\modelo\Modelo;
use mvc\modelo\orm\Mvc_Orm_Articulos;

class M_Main implements Modelo {

    public function despacha(): mixed {

        // Si el usuario se ha autenticado
        if( isset($_COOKIE['jwt'])) {
            $this->con_usuario_autenticado();
        }
        else {
            $this->sin_usuario_autenticado();
        }

        $orm_articulos = new Mvc_Orm_Articulos();
        $articulos_oferta = $orm_articulos->get_ofertas();

        return $articulos_oferta;
        
    }

    private function sin_usuario_autenticado() {
        $_SESSION['carrito'] = [];
    }

    private function con_usuario_autenticado() {

    }
}
?>