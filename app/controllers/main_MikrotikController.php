<?php
require __DIR__ . '/../../config.php';
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../db_connection.php';
require __DIR__ . '/../util.php'; // Incluir el archivo de utilidades

use phpseclib3\Net\SSH2;
use phpseclib3\Exception\UnableToConnectException;

$log_file = LOG_PATH . 'main_mikrotik_log.txt';
$failed_log = LOG_PATH . 'fallas_dispositivos.txt';

$connections = [];

while (true) {
    // Obtener clientes deshabilitados
    $stmt = $db->query("SELECT producto_ip, categoria_id FROM producto WHERE producto_estado = 'Deshabilitado'");
    $disabled_clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($disabled_clients as $client) {
        $ip = $client['producto_ip'];
        $categoria_id = $client['categoria_id'];

        // Obtener IP y puerto del router desde la base de datos
        $stmt = $db->prepare("SELECT categoria_ip, categoria_puerto FROM categoria WHERE categoria_id = ?");
        $stmt->execute([$categoria_id]);
        $router = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($router) {
            $router_ip = $router['categoria_ip'];
            $router_port = $router['categoria_puerto'];

            // Conectar o reconectar al router
            if (!isset($connections[$router_ip])) {
                $ssh = new SSH2($router_ip, $router_port);
                try {
                    if ($ssh->login('frabe', 'Fr4b3c0rp#12')) {
                        $connections[$router_ip] = $ssh;
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
                $ssh = $connections[$router_ip];
            }

            try {
                $command = "ip firewall address-list add list=BLOCKED_USERS address=$ip";
                $ssh->exec($command);

                $lines = manage_log_file($log_file);
                $lines[] = "[" . date('Y-m-d H:i:s') . "] IP añadida al firewall: $ip en router: $router_ip\n";
                file_put_contents($log_file, implode('', $lines));
            } catch (UnableToConnectException $e) {
                unset($connections[$router_ip]); // Eliminar la conexión fallida
                $lines = manage_log_file($failed_log);
                $lines[] = "[" . date('Y-m-d H:i:s') . "] Error de conexión SSH para router: $router_ip. Error: " . $e->getMessage() . "\n";
                file_put_contents($failed_log, implode('', $lines));
            }
        } else {
            $lines = manage_log_file($failed_log);
            $lines[] = "[" . date('Y-m-d H:i:s') . "] Router Mikrotik no encontrado para categoria_id: $categoria_id\n";
            file_put_contents($failed_log, implode('', $lines));
        }
    }

    // Obtener clientes habilitados
    $stmt = $db->query("SELECT producto_ip, categoria_id FROM producto WHERE producto_estado = 'Habilitado'");
    $enabled_clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($enabled_clients as $client) {
        $ip = $client['producto_ip'];
        $categoria_id = $client['categoria_id'];

        // Obtener IP y puerto del router desde la base de datos
        $stmt = $db->prepare("SELECT categoria_ip, categoria_puerto FROM categoria WHERE categoria_id = ?");
        $stmt->execute([$categoria_id]);
        $router = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($router) {
            $router_ip = $router['categoria_ip'];
            $router_port = $router['categoria_puerto'];

            // Conectar o reconectar al router
            if (!isset($connections[$router_ip])) {
                $ssh = new SSH2($router_ip, $router_port);
                try {
                    if ($ssh->login('frabe', 'Fr4b3c0rp#12')) {
                        $connections[$router_ip] = $ssh;
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
                $ssh = $connections[$router_ip];
            }

            try {
                $command = "ip firewall address-list remove [find list=BLOCKED_USERS address=$ip]";
                $ssh->exec($command);

                $lines = manage_log_file($log_file);
                $lines[] = "[" . date('Y-m-d H:i:s') . "] IP eliminada del firewall: $ip en router: $router_ip\n";
                file_put_contents($log_file, implode('', $lines));
            } catch (UnableToConnectException $e) {
                unset($connections[$router_ip]); // Eliminar la conexión fallida
                $lines = manage_log_file($failed_log);
                $lines[] = "[" . date('Y-m-d H:i:s') . "] Error de conexión SSH para router: $router_ip. Error: " . $e->getMessage() . "\n";
                file_put_contents($failed_log, implode('', $lines));
            }
        } else {
            $lines = manage_log_file($failed_log);
            $lines[] = "[" . date('Y-m-d H:i:s') . "] Router Mikrotik no encontrado para categoria_id: $categoria_id\n";
            file_put_contents($failed_log, implode('', $lines));
        }
    }

    sleep(WAIT_TIME);
}
?>
