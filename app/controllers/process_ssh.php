<?php
require __DIR__ . '/../../config.php';
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../db_connection.php';
require __DIR__ . '/../util.php';

use phpseclib3\Net\SSH2;
use phpseclib3\Exception\UnableToConnectException;

if ($argc !== 3) {
    exit("Uso: php process_ssh.php <producto_id> <nuevo_estado>\n");
}

$producto_id = $argv[1];
$nuevo_estado = $argv[2];
$comando = $nuevo_estado === 'habilitado' ? 'flash set CATV_ENABLED 1' : 'flash set CATV_ENABLED 0';

$log_file = LOG_PATH . 'offcontroller_log.txt';
$failed_log = LOG_PATH . 'failed_ips.txt';

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
        sleep($nuevo_estado === 'habilitado' ? 35 : 0);

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
?>
