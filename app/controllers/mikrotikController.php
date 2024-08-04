<?php
// Incluir configuración de Redis
require 'redis_config.php';
// Incluir el archivo de utilidades
require __DIR__ . '/../util.php'; 
// Incluir archivo config
require __DIR__ . '/../../config.php';


// Función para registrar eventos en el archivo de log
function log_event($message, $log_file) {
    $date = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$date] $message\n", FILE_APPEND);
}

// Configuración de la base de datos
$db_host = 'localhost';
$db_user = 'root'; // Cambia si tienes un usuario diferente
$db_pass = '';     // Cambia si tienes una contraseña
$db_name = 'sistemaredes';

// Conectar a la base de datos
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verificar la conexión
if ($mysqli->connect_error) {
    die('Error de conexión a la base de datos: ' . $mysqli->connect_error);
}

// Configuración de las credenciales de los routers
$router_user = 'frabe';
$router_pass = 'Fr4b3c0rp#12';

// Archivo de log
$log_file = LOG_PATH . 'router_connections.log';

// Obtener la información de conexión de la tabla categoria
$query_categoria = 'SELECT categoria_id, categoria_ip, categoria_puerto, categoria_nombre FROM categoria';
$result_categoria = $mysqli->query($query_categoria);

if (!$result_categoria) {
    die('Error en la consulta: ' . $mysqli->error);
}

$connections = [];
$failed_connections = [];

// Conectar a cada router y mantener la conexión
while ($categoria = $result_categoria->fetch_assoc()) {
    $categoria_id = $categoria['categoria_id'];
    $router_ip = $categoria['categoria_ip'];
    $router_port = $categoria['categoria_puerto'];
    $router_name = $categoria['categoria_nombre'];

    // Conectar al router MikroTik por SSH
    $connection = @ssh2_connect($router_ip, $router_port);
    if (!$connection) {
        log_event("No se pudo establecer la conexión SSH con el router $router_name ($router_ip)", $log_file);
        $failed_connections[] = [
            'id' => $categoria_id,
            'name' => $router_name,
            'ip' => $router_ip,
            'port' => $router_port,
            'last_attempt' => time()
        ];
        continue;
    }

    // Autenticar
    if (!@ssh2_auth_password($connection, $router_user, $router_pass)) {
        log_event("Error de autenticación SSH en el router $router_name ($router_ip)", $log_file);
        $failed_connections[] = [
            'id' => $categoria_id,
            'name' => $router_name,
            'ip' => $router_ip,
            'port' => $router_port,
            'last_attempt' => time()
        ];
        continue;
    }

    log_event("Conexión SSH exitosa con el router $router_name ($router_ip)", $log_file);
    $connections[] = [
        'id' => $categoria_id,
        'name' => $router_name,
        'ip' => $router_ip,
        'connection' => $connection
    ];
}

// Función para intentar reconectar a routers fallidos
function retry_connections(&$failed_connections, &$connections, $router_user, $router_pass, $log_file) {
    foreach ($failed_connections as $key => $failed) {
        if (time() - $failed['last_attempt'] >= 1200) {
            $connection = @ssh2_connect($failed['ip'], $failed['port']);
            if ($connection && @ssh2_auth_password($connection, $router_user, $router_pass)) {
                log_event("Reconexión exitosa con el router {$failed['name']} ({$failed['ip']})", $log_file);
                $connections[] = [
                    'id' => $failed['id'],
                    'name' => $failed['name'],
                    'ip' => $failed['ip'],
                    'connection' => $connection
                ];
                unset($failed_connections[$key]);
            } else {
                log_event("Fallo en el intento de reconexión con el router {$failed['name']} ({$failed['ip']})", $log_file);
                $failed_connections[$key]['last_attempt'] = time();
            }
        }
    }
}

// Función para procesar los eventos de cambios
function process_product_changes($mysqli, &$connections, $log_file) {
    $query = "SELECT id, producto_id, nuevo_estado FROM producto_cambios_eventos WHERE procesado = 0";
    $result = $mysqli->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $producto_id = $row['producto_id'];
            $nuevo_estado = $row['nuevo_estado'];

            // Obtener la IP y la categoría del producto
            $query_producto = "SELECT producto_ip, categoria_id FROM producto WHERE producto_id = ?";
            $stmt = $mysqli->prepare($query_producto);
            $stmt->bind_param('i', $producto_id);
            $stmt->execute();
            $producto_result = $stmt->get_result();

            if ($producto_result->num_rows > 0) {
                $producto_row = $producto_result->fetch_assoc();
                $ip = $producto_row['producto_ip'];
                $categoria_id = $producto_row['categoria_id'];

                $processed = false;
                foreach ($connections as $conn) {
                    if ($categoria_id == $conn['id']) {
                        $router_ip = $conn['ip'];
                        $router_name = $conn['name'];
                        $connection = $conn['connection'];

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
                            echo "Error al ejecutar el comando para la IP $ip en el router $router_name ($router_ip)\n";
                        } else {
                            log_event("IP $ip $action del firewall del router $router_name ($router_ip). Salida: $output", $log_file);
                            echo "IP $ip $action del firewall del router $router_name ($router_ip).\n";
                            $processed = true;
                        }
                    }
                }

                if (!$processed) {
                    // Si no se procesó, se mantiene el evento en la tabla para futuros intentos
                    log_event("El cambio de estado para la IP $ip no se pudo procesar debido a la falta de conexión con el router correspondiente.", $log_file);
                } else {
                    // Marcar el evento como procesado
                    $update_query = "UPDATE producto_cambios_eventos SET procesado = 1 WHERE id = ?";
                    $update_stmt = $mysqli->prepare($update_query);
                    $update_stmt->bind_param('i', $row['id']);
                    $update_stmt->execute();
                    $update_stmt->close();
                }
            }

            $stmt->close();
        }
    } else {
        log_event('Error en la consulta de cambios de productos: ' . $mysqli->error, $log_file);
    }
}

// Procesar cambios de productos cada minuto
while (true) {
    process_product_changes($mysqli, $connections, $log_file);
    retry_connections($failed_connections, $connections, $router_user, $router_pass, $log_file);
    sleep(60); // Esperar un minuto antes de la siguiente verificación
}

$mysqli->close();
?>
