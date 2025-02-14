<?php
// Ejemplo de uso de un servicio redis

$servicio_redis = new Redis();
$servicio_redis->connect("127.0.0.1", 6379);

// Verificar la conexión
if( $servicio_redis->ping() ) {
    echo "<h3>El servicio redis está en funcionamiento</h3>";
}

// Guardar un valor simple
$servicio_redis->set("usuario", "pepe");

// Recuperar un valor guardado
$usuario = $servicio_redis->get("usuario");

echo "El usuario es $usuario<br>";

// Almacenamiento de un valor temporal
$servicio_redis->setex("numero_temporal", 20, 1234);
$numero_temporal = $servicio_redis->getex("numero_temporal");
echo "El número temporal es $numero_temporal<br>";

// Uso de listas
$servicio_redis->lpush("cola", "Tarea 1");
$servicio_redis->lpush("cola", "Tarea 2");
$servicio_redis->lpush("cola", "Tarea 3");

$array_tareas = $servicio_redis->lrange("cola", 0, -1);
echo "La cola es: " . implode(" - " , $array_tareas) . "<br>";

$tarea = $servicio_redis->lpop("cola");
echo "La tarea es $tarea<br>";

// Uso de hashes
$servicio_redis->hset("usuario:admin", "nombre", "Juan");
$usuario = $servicio_redis->hgetall("usuario:admin");
echo "El hash de admin es {$usuario['nombre']}<br>";
?>