<?php
require __DIR__ . '/../../config.php';
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../db_connection.php';
require __DIR__ . '/../util.php'; // Asegúrate de que este archivo exista

use phpseclib3\Net\SSH2;
use phpseclib3\Exception\UnableToConnectException;

$log_file = LOG_PATH . 'mikrotik_log.txt';
$failed_log = LOG_PATH . 'fallas_dispositivos.txt';

$ssh_connections = [];

while (true) {
    $date = date('d');
    if ($date == '15') {
        $stmt = $db->query("SELECT producto_ip, categoria_id FROM producto WHERE producto_estado = 'Deshabilitado'");
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($clients as $client) {
            $ip = $client['producto_ip'];
            $categoria_id = $client['categoria_id'];

            $stmt = $db->prepare("SELECT categoria_ip, categoria_puerto FROM categoria WHERE categoria_id = ?");
            $stmt->execute([$categoria_id]);
            $router = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($router) {
                $router_ip = $router['categoria_ip'];
                $router_port = $router['categoria_puerto'];

                // Conectar al router si no está ya conectado
                if (!isset($ssh_connections[$router_ip])) {
                    $ssh = new SSH2($router_ip, $router_port);
                    try {
                        if ($ssh->login(SSH_USER, SSH_PASS)) {
                            $ssh_connections[$router_ip] = $ssh;
                        } else {
                            throw new UnableToConnectException("Fallo de autenticación SSH en el router: $router_ip");
                        }
                    } catch (UnableToConnectException $e) {
                        $lines = manage_log_file($failed_log);
                        $lines[] = "[" . date('Y-m-d H:i:s') . "] Error de conexión SSH para router: $router_ip. Error: " . $e->getMessage() . "\n";
                        file_put_contents($failed_log, implode('', $lines));
                        continue;
                    }
                } else {
                    $ssh = $ssh_connections[$router_ip];
                }

                try {
                    $command = "ip firewall address-list add list=BLOCKED_USERS address=$ip";
                    $ssh->exec($command);

                    $lines = manage_log_file($log_file);
                    $lines[] = "[" . date('Y-m-d H:i:s') . "] IP añadida al firewall: $ip en router: $router_ip\n";
                    file_put_contents($log_file, implode('', $lines));
                } catch (Exception $e) {
                    $lines = manage_log_file($failed_log);
                    $lines[] = "[" . date('Y-m-d H:i:s') . "] Error al ejecutar comando en el router: $router_ip. Error: " . $e->getMessage() . "\n";
                    file_put_contents($failed_log, implode('', $lines));
                }
            } else {
                $lines = manage_log_file($failed_log);
                $lines[] = "[" . date('Y-m-d H:i:s') . "] Router Mikrotik no encontrado para categoria_id: $categoria_id\n";
                file_put_contents($failed_log, implode('', $lines));
            }
        }
    }

    // Reintentar conexiones caídas
    foreach ($ssh_connections as $router_ip => $ssh) {
        if (!$ssh->isConnected()) {
            unset($ssh_connections[$router_ip]);
            $lines = manage_log_file($failed_log);
            $lines[] = "[" . date('Y-m-d H:i:s') . "] Conexión perdida con el router: $router_ip. Intentando reconectar...\n";
            file_put_contents($failed_log, implode('', $lines));
        }
    }

    sleep(WAIT_TIME);
}
?>
