<?php
namespace mvc\modelo;

use Exception;
use mvc\modelo\orm\Mvc_Orm_Clientes;
use mvc\modelo\orm\Mvc_Orm_LEnvio;
use util\seguridad\JWT;

class M_Autenticar implements Modelo {

    public function despacha(): mixed {
        
        $datos = $this->sanear_y_validar();
        if( $this->autentica_cliente($datos) ) {
            // Obtenemos los últimos envíos
            $orm_envios = new Mvc_Orm_LEnvio();
            $cliente = $_SESSION['cliente'];
            $envios = $orm_envios->get_envios($cliente->nif);
            return $envios;
        }
        else {
            throw new Exception("La autenticación no ha tenido éxito", 4003);
        }

    }

    public function sanear_y_validar(): array {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $clave = $_POST['clave'];

        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if( $email ) {
            return ['email' => $email, 'clave' => $clave];
        }
        else {
            throw new Exception("El email no es válido", 4001);
        }
    }

    public function autentica_cliente(array $datos): bool {
        $orm_cliente = new Mvc_Orm_Clientes();
        $cliente = $orm_cliente->busca_cliente($datos['email']);

        // Si el cliente no existe, devuelve null
        if( !$cliente ) throw new Exception("El cliente no existe", 4002);

        // Si el cliente existe, comprobar la clave
        if( password_verify($datos['clave'], $cliente->clave) ) {
            
            // Crear el JWT
            $payload = ['nombre' => $cliente->nombre,
                        'apellidos' => $cliente->apellidos,
                        'email' => $cliente->email];
            $jwt = JWT::generar_token($payload);
            $duracion_sesion = ini_get("session.gc_maxlifetime");

            setcookie("jwt", $jwt, time() + $duracion_sesion,"/", "dwes.com", false, true );

            $_SESSION['cliente'] = $cliente;

            return true;

        }
        else {
            throw new Exception("La autenticación no ha tenido éxito", 4007);
        }

    }

    

    
}
?>