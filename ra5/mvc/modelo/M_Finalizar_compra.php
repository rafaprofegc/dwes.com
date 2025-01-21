<?php
namespace mvc\modelo;

use mvc\modelo\Modelo;
use util\seguridad\JWT;
use Exception;
use mvc\modelo\orm\Mvc_Orm_Direccion_Envio;
use orm\modelo\ORMForma_Envio;

class M_Finalizar_compra implements Modelo {

    public function despacha(): mixed {
        // 1º Comprobar si hay abierta sesión
        // si no la hay, se tiene que autenticar


        // Si está autenticado,
        // Obtener de la BBDD sus direcciones de envío
        // Obtener de la BBDD las formas de envío
        
        if( isset($_COOKIE['jwt'])) {
            $payload = JWT::verificar_token($_COOKIE['jwt']);
            if( !$payload ) {
                throw new Exception("No se ha podido verificar la identidad", 4004);
            }

            $direcciones_envio = $this->obtener_direcciones_envio();
            $formas_envio = $this->obtener_formas_envio();

            return ['direcciones_envio' => $direcciones_envio, 
                    'formas_envio'      => $formas_envio];
        }
        else {
            return null;
        }
    }

    private function obtener_direcciones_envio(): array {
        $orm_dir_env = new Mvc_Orm_Direccion_Envio();
        $cliente = $_SESSION['cliente'];
        $direcciones_envio = $orm_dir_env->getDireccionesCliente($cliente->nif);

        return $direcciones_envio;
    }

    private function obtener_formas_envio(): array {
        $orm_formas_envio = new ORMForma_Envio();
        $formas_envio = $orm_formas_envio->getAll();
        return $formas_envio;
    }
}

?>