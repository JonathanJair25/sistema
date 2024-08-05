<?php
require 'redis_config.php';
require __DIR__ . '/../util.php'; 
require __DIR__ . '/../../config.php';
require __DIR__ . '/../db_connection.php';
require __DIR__ . '/../../vendor/autoload.php'; // Asegúrate de que esta ruta es correcta

use phpseclib3\Net\SSH2;

// Configuración de la base de datos
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'sistemaredes';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    die('Error de conexión a la base de datos: ' . $mysqli->connect_error);
}

// Datos de conexión al router
$router_user = 'frabe';
$router_pass = 'Fr4b3c0rp#12';
$log_file = LOG_PATH . 'router_connections.log';
$max_parallel_processes = 10;
$current_processes = [];

// Consulta para obtener información de los routers
$query_categoria = 'SELECT categoria_id, categoria_ip, categoria_puerto, categoria_nombre FROM categoria';
$result_categoria = $mysqli->query($query_categoria);

if (!$result_categoria) {
    die('Error en la consulta: ' . $mysqli->error);
}

$connections = [];
$failed_connections = [];

// Función para conectar al router
function connect_router($router_ip, $router_port, $router_user, $router_pass, $router_name, $log_file) {
    $ssh = new SSH2($router_ip, $router_port);
    if (!$ssh->login($router_user, $router_pass)) {
        log_event("Error de autenticación SSH en el router $router_name ($router_ip)", $log_file);
        return false;
    }
    log_event("Conexión SSH exitosa con el router $router_name ($router_ip)", $log_file);
    return $ssh;
}

// Establecer conexiones iniciales
while ($categoria = $result_categoria->fetch_assoc()) {
    $categoria_id = $categoria['categoria_id'];
    $router_ip = $categoria['categoria_ip'];
    $router_port = $categoria['categoria_puerto'];
    $router_name = $categoria['categoria_nombre'];

    $ssh = connect_router($router_ip, $router_port, $router_user, $router_pass, $router_name, $log_file);
    if ($ssh) {
        $connections[$categoria_id] = $ssh;
    } else {
        $failed_connections[] = [
            'id' => $categoria_id,
            'name' => $router_name,
            'ip' => $router_ip,
            'port' => $router_port,
            'last_attempt' => time()
        ];
    }
}

// Función para reintentar conexiones fallidas
function retry_connections(&$failed_connections, &$connections, $router_user, $router_pass, $log_file) {
    foreach ($failed_connections as $key => $failed) {
        if (time() - $failed['last_attempt'] >= 1200) { // 20 minutos
            $ssh = connect_router($failed['ip'], $failed['port'], $router_user, $router_pass, $failed['name'], $log_file);
            if ($ssh) {
                $connections[$failed['id']] = $ssh;
                unset($failed_connections[$key]);
            } else {
                $failed_connections[$key]['last_attempt'] = time();
            }
        }
    }
}

// Función para procesar cambios de producto
function process_product_changes($mysqli, &$connections, $log_file, &$current_processes, $max_parallel_processes) {
    $query = "SELECT id, producto_id, nuevo_estado FROM producto_cambios_eventos WHERE procesado = 0";
    $result = $mysqli->query($query);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $producto_id = $row['producto_id'];
            $nuevo_estado = $row['nuevo_estado'];

            // Esperar hasta que haya espacio en el pool de procesos
            while (count($current_processes) >= $max_parallel_processes) {
                foreach ($current_processes as $key => $process) {
                    $status = proc_get_status($process);
                    if (!$status['running']) {
                        proc_close($process);
                        unset($current_processes[$key]);
                    }
                }
                sleep(1);
            }

            $cmd = 'php process_router.php ' . escapeshellarg($producto_id) . ' ' . escapeshellarg($nuevo_estado);
            $process = proc_open($cmd, [], $pipes);

            if (is_resource($process)) {
                $current_processes[] = $process;
            }
        }
    } else {
        log_event('Error en la consulta de cambios de productos: ' . $mysqli->error, $log_file);
    }
}

while (true) {
    process_product_changes($mysqli, $connections, $log_file, $current_processes, $max_parallel_processes);
    retry_connections($failed_connections, $connections, $router_user, $router_pass, $log_file);
    sleep(30);
}

$mysqli->close();
?>
