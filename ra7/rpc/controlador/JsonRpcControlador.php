<?php
namespace rpc\controlador;
use Exception;

class JsonRpcControlador {
    private string $espacio_nombres_modelo = "rpc\\modelo\\";

    public function manejarPeticion() {

        //1º Obtener el cuerpo de la petición
        $cuerpo = file_get_contents("php://input");

        // Formato de la petición
        /*
           { "jsonrpc": "2.0",
             "method": "Clase.metodo",
             "params": [par1, par2, ...],
             "id": 1234
           }
        */
        $peticion = json_decode($cuerpo, true);

        // 2º Comprobar que la petición es válida
        if( !$this->esPeticionValida($peticion) ) {
            $this->enviarRespuesta(null, null, ['code' => -32600, 'message' => 'Invalid Request']);
            return;
        }

        // 3º Guardar el id
        $id = $peticion['id'] ?? null;

        //4º  Obtener el modelo y el método
        try {
            [$modelo, $metodo] = $this->obtenerModeloMetodo($peticion['method']);
            $claseModelo = $this->espacio_nombres_modelo . $modelo;

            if( class_exists($claseModelo) && method_exists($claseModelo, $metodo) ) {
                // 5º Instanciar la clase
                $objetoModelo = new $claseModelo();

                // 6º Obtenemos los parámetros
                $parametros = $peticion['params'] ?? [];

                // 7º Invocar el método de la clase
                // Probar $objetoModelo->$metodo(...$parametros);
                $resultado = call_user_func_array([$objetoModelo, $metodo], $parametros);

                // 8º Devolución del resultado
                $this->enviarRespuesta($id, $resultado, null);
            }
            else {
                $this->enviarRespuesta($id, null, ['code' => -32601, 'message' => 'Method not found']);
            }
        }
        catch(Exception $e) {
            $this->enviarRespuesta($id, null, ['code' => -32603, 'message' => 'Intenal error',
                                               'data' => $e->getMessage()]);
        }

    }

    private function esPeticionValida($peticion) {
        return isset($peticion['jsonrpc'], $peticion['method']) && $peticion['jsonrpc'] === "2.0";
    }

    private function enviarRespuesta($id, $resultado, $error):void {
        $respuesta['jsonrpc'] = "2.0";

        if( $resultado ) {
            $respuesta['result'] = $resultado;
        }

        if( $error ) {
            $respuesta['error'] = $error;
        }

        $respuesta['id'] = $id;

        header("Content-Type: application/json");
        echo json_encode($respuesta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

        /*
        Sin PRETTY_PRINT
        {"jsonrpc": "2.0","result": 12,"id":1234}

        Con PRETTY_PRINT
        {
            "jsonrpc": "2.0",
            "result": 12,
            "id":1234
        }
        */
    }

    private function obtenerModeloMetodo($metodo): array {
        if( !strpos($metodo, ".") ) {
            throw new Exception("El formato del método no es correcto. Utilizar: Clase.metodo");
        }

        // Matematicas.sumar    -> ['Matematicas','sumar'];
        return explode(".", $metodo);
    }

}
?>