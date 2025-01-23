<?php
namespace rpc\modelo;

/* Formato de la petición:
{
    "jsonrpc" : "2.0",
    "method" : "Matematicas.suma",
    "params" : [3, 8],
    "id": 1234
}
*/

class Matematicas {
    public function suma($a, $b) {
        return $a + $b;
    }
    public function resta($a, $b) {
        return $a - $b;
    }
    public function multiplica($a, $b) {
        return $a * $b;
    }
}
?>