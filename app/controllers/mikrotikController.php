<?php
require 'redis_config.php';
require __DIR__ . '/../util.php'; 
require __DIR__ . '/../../config.php';

// Función para registrar eventos en el archivo de log con un máximo de 1000 líneas
function log_event($message, $log_file) {
    $date = date('Y-m-d H:i:s');
    $log_entry = "[$date] $message\n";

    // Verifica si el archivo de log existe
    if (file_exists($log_file)) {
        $lines = file($log_file, FILE_IGNORE_NEW_LINES);

        // Si el log tiene 1000 líneas o más, lo reinicia
        if (count($lines) >= 1000) {
            file_put_contents($log_file, $log_entry);
        } else {
            file_put_contents($log_file, $log_entry, FILE_APPEND);
        }
    } else {
        file_put_contents($log_file, $log_entry);
    }
}

// Configuración de la base de datos
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
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

            $cmd = 'php process_router.php ' . escapeshellarg($producto_id) . ' ' . escapeshellarg($nuevo_estado);
            $process = proc_open($cmd, [], $pipes);

            if (is_resource($process)) {
                proc_close($process);
            }
        }
    } else {
        log_event('Error en la consulta de cambios de productos: ' . $mysqli->error, $log_file);
    }
}

// Procesar cambios de productos cada minuto
while (true) {
    process_product_changes($mysqli, $connections, $log_file);
    retry_connections($failed_connections, $connections, $router_user, $router_pass, $log_file);
    sleep(30);
}

$mysqli->close();
?>
