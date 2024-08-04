<?php
// Configuración de Redis
$redis_host = 'localhost';
$redis_port = 6780;

// Crear una instancia de Redis
$redis = new Redis();
try {
    $redis->connect($redis_host, $redis_port);
    echo "Conexión a Redis exitosa.\n"; // Puedes eliminar esto en producción
} catch (RedisException $e) {
    die("No se puede conectar a Redis: " . $e->getMessage());
}
?>
