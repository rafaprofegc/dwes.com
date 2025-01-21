<?php
namespace mvc\modelo;

use mvc\modelo\Modelo;
use util\seguridad\JWT;
use Exception;
use orm\modelo\ORMPedido;
use orm\entidad\Pedido;
use DateTime;
use PDOException;

class M_Crear_pedido implements Modelo {

    public function despacha(): mixed {
        
        // 1º El cliente tiene que estar autenticado
        if( isset($_COOKIE['jwt']) ) {
            $payload = JWT::verificar_token($_COOKIE['jwt']);
            if( !$payload ) {
                throw new Exception("El token no ha pasado la verificación", 4005);
            }
        }

        // 2º Saneamiento de dirección de envío y forma de envío.
        $direccion_envio = filter_input(INPUT_POST, 'direccion_envio', FILTER_SANITIZE_SPECIAL_CHARS);
        $forma_envio = filter_input(INPUT_POST, 'forma_envio', FILTER_SANITIZE_SPECIAL_CHARS);

        try {          

            // 3º Insertar en la BBDD:
            // - Tabla pedido
            $orm_pedido = new ORMPedido();

            // Empezar una transacción
            $orm_pedido->pdo->beginTransaction();

            /* npedido, nif, fecha, observaciones, total_pedido */
            $cliente = $_SESSION['cliente'];
            $datos_pedido = [null, $cliente->nif, new DateTime(), null, 0];
            $pedido = new Pedido($datos_pedido);
            $orm_pedido->insert($pedido);

            // - Tabla lpedido
            // Se inserta npedido, nº línea, referencia, unidades, precio, dto 

            $npedido = $orm_pedido->pdo->lastInsertId();
            $nlinea = 1;
            $orm_lpedido = new ORMLPedido();
            foreach($_SESSION['carrito'] as $articulo ) {
                $lpedido = new LPedido($npedido, $nlinea, $articulo->referencia, 
                                       1, $articulo->pvp, $articulo->dto_venta);
                $orm_lpedido->insert($lpedido);
                $nlinea++;
            }


            // - Tabla envio
            
            // - Tabla lenvio

            $orm_pedido->pdo->commit();
        }
        catch(PDOException $pdoe ) {
            $orm_pedido->pdo->rollBack();
            throw $pdoe;
        }


    }
}
?>