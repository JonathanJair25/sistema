<?php
require __DIR__ . '/../../config.php';
require __DIR__ . '/../util.php';
require __DIR__ . '/../db_connection.php';

use phpseclib3\Net\SSH2;
use phpseclib3\Exception\UnableToConnectException;

if ($argc !== 3) {
    exit("Uso: php process_router.php <producto_id> <nuevo_estado>\n");
}

$producto_id = $argv[1];
$nuevo_estado = $argv[2];
$log_file = LOG_PATH . 'router_connections.log';

$stmt = $db->prepare("SELECT producto_ip, categoria_id FROM producto WHERE producto_id = ?");
$stmt->execute([$producto_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    $ip = $row['producto_ip'];
    $categoria_id = $row['categoria_id'];

    $stmt_categoria = $db->prepare("SELECT categoria_ip, categoria_puerto, categoria_nombre FROM categoria WHERE categoria_id = ?");
    $stmt_categoria->execute([$categoria_id]);
    $categoria = $stmt_categoria->fetch(PDO::FETCH_ASSOC);

    if ($categoria) {
        $router_ip = $categoria['categoria_ip'];
        $router_port = $categoria['categoria_puerto'];
        $router_name = $categoria['categoria_nombre'];
        $connection = @ssh2_connect($router_ip, $router_port);

        if ($connection && @ssh2_auth_password($connection, SSH_USER, SSH_PASS)) {
            if ($nuevo_estado == 'deshabilitado') {
                $command = "ip firewall address-list add list=BLOCKED_USERS address=$ip";
                $action = "añadida";
            } else {
                $command = "ip firewall address-list remove [find list=BLOCKED_USERS address=$ip]";
                $action = "eliminada";
            }

            $stream = ssh2_exec($connection, $command);
            stream_set_blocking($stream, true);
            $output = stream_get_contents($stream);
            fclose($stream);

            if ($output === false) {
                log_event("Error al ejecutar el comando para la IP $ip en el router $router_name ($router_ip). Salida: $output", $log_file);
            } else {
                log_event("IP $ip $action del firewall del router $router_name ($router_ip). Salida: $output", $log_file);
                $update_query = "UPDATE producto_cambios_eventos SET procesado = 1 WHERE producto_id = ?";
                $update_stmt = $db->prepare($update_query);
                $update_stmt->execute([$producto_id]);
            }
        } else {
            log_event("Error de conexión o autenticación SSH en el router $router_name ($router_ip)", $log_file);
        }
    }
}
?>
