<?php
require __DIR__ . '/../../config.php';
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../db_connection.php';

use phpseclib3\Net\SSH2;
use phpseclib3\Exception\UnableToConnectException;

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

$log_file = LOG_PATH . 'offcontroller_log.txt';
$failed_log = LOG_PATH . 'failed_ips.txt';

while (true) {
    $item = $redis->lpop('estado_actualizado');
    
    if ($item) {
        $data = json_decode($item, true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            $producto_id = isset($data['producto_id']) ? $data['producto_id'] : null;
            $nuevo_estado = isset($data['nuevo_estado']) ? $data['nuevo_estado'] : null;

            if ($producto_id !== null && $nuevo_estado !== null) {
                $comando = $nuevo_estado === 'habilitado' ? 'flash set CATV_ENABLED 1' : 'flash set CATV_ENABLED 0';

                $stmt = $db->prepare("SELECT p.producto_ip, p.producto_nombre, p.producto_apellidos, c.categoria_nombre FROM producto p JOIN categoria c ON p.categoria_id = c.categoria_id WHERE p.producto_id = ?");
                $stmt->execute([$producto_id]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($row) {
                    $ip_address = $row['producto_ip'];
                    $nombre = $row['producto_nombre'];
                    $apellidos = $row['producto_apellidos'];
                    $organizacion = $row['categoria_nombre'];
                    $ssh = new SSH2($ip_address);
                    
                    try {
                        if ($ssh->login(SSH_USER, SSH_PASS)) {
                            $ssh->exec($comando);
                            $lines = manage_log_file($log_file);
                            $lines[] = "[" . date('Y-m-d H:i:s') . "] Comando SSH ejecutado: $comando para producto_id: $producto_id\n";
                            file_put_contents($log_file, implode('', $lines));
                        } else {
                            throw new UnableToConnectException("Fallo de autenticación SSH para producto_id: $producto_id, IP: $ip_address");
                        }
                    } catch (UnableToConnectException $e) {
                        $lines = manage_log_file($failed_log);
                        $lines[] = "[" . date('Y-m-d H:i:s') . "] $nombre $apellidos, IP: $ip_address, Organización: $organizacion\n";
                        file_put_contents($failed_log, implode('', $lines));
                    }
                } else {
                    $lines = manage_log_file($failed_log);
                    $lines[] = "[" . date('Y-m-d H:i:s') . "] Producto no encontrado en la base de datos para producto_id: $producto_id\n";
                    file_put_contents($failed_log, implode('', $lines));
                }
            } else {
                $lines = manage_log_file($failed_log);
                $lines[] = "[" . date('Y-m-d H:i:s') . "] Datos incompletos en el mensaje de Redis: producto_id o nuevo_estado no definidos.\n";
                file_put_contents($failed_log, implode('', $lines));
            }
        } else {
            $lines = manage_log_file($failed_log);
            $lines[] = "[" . date('Y-m-d H:i:s') . "] Error de decodificación JSON para producto_id: $data[producto_id] - Error: " . json_last_error_msg() . "\n";
            file_put_contents($failed_log, implode('', $lines));
        }
    }

    sleep(WAIT_TIME);
}
?>
