<?php
require __DIR__ . '/../../config.php';
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../db_connection.php';

$redis = new Predis\Client();

function manage_log_file($file_path) {
    $max_lines = 1000;
    if (file_exists($file_path)) {
        $lines = file($file_path);
        if (count($lines) >= $max_lines) {
            $lines = array_slice($lines, -($max_lines - 1));
        }
    } else {
        $lines = [];
    }
    return $lines;
}

$log_file = LOG_PATH . 'monitor_log.txt';

while (true) {
    $stmt = $db->query("SELECT * FROM producto_cambios_monitor WHERE procesado = 0");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $producto_id = $row['producto_id'];
        $nuevo_estado = $row['nuevo_estado'];

        $lines = manage_log_file($log_file);
        $lines[] = "[" . date('Y-m-d H:i:s') . "] Cambio detectado para producto_id: $producto_id, nuevo_estado: $nuevo_estado\n";
        file_put_contents($log_file, implode('', $lines));

        $value = json_encode([
            'producto_id' => $producto_id,
            'nuevo_estado' => $nuevo_estado
        ]);

        if ($value !== false && is_string($value)) {
            $redis->rpush('estado_actualizado', [$value]);
            $lines[] = "[" . date('Y-m-d H:i:s') . "] Actualización de Redis exitosa para producto_id: $producto_id\n";
            file_put_contents($log_file, implode('', $lines));
        } else {
            $lines[] = "[" . date('Y-m-d H:i:s') . "] Error al codificar JSON para producto_id: $producto_id\n";
            file_put_contents($log_file, implode('', $lines));
        }

        $db->prepare("UPDATE producto_cambios_monitor SET procesado = 1 WHERE id = ?")
            ->execute([$row['id']]);
    }

    sleep(WAIT_TIME);
}
?>
