<?php
require __DIR__ . '/../../vendor/autoload.php'; // Asegúrate de que esta ruta es correcta
require __DIR__ . '/../util.php'; // Incluye el archivo util.php que contiene log_event
require __DIR__ . '/../../config.php';
require __DIR__ . '/../db_connection.php';

use phpseclib3\Net\SSH2;
use phpseclib3\Exception\UnableToConnectException;

// Conexión a la base de datos
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'sistemaredes';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($mysqli->connect_error) {
    die('Error de conexión a la base de datos: ' . $mysqli->connect_error);
}

// Obtener los argumentos del script
$producto_id = $argv[1];
$nuevo_estado = $argv[2];

// Consultar los detalles del producto
$query = "SELECT producto_ip FROM producto WHERE producto_id = ?";
$stmt = $mysqli->prepare($query);
if ($stmt === false) {
    log_event("Error en la preparación de la consulta: " . $mysqli->error, LOG_PATH . 'router_connections.log');
    exit(1);
}
$stmt->bind_param('i', $producto_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    log_event("No se encontró el cliente con ID $producto_id", LOG_PATH . 'router_connections.log');
    exit(1);
}

$row = $result->fetch_assoc();
$producto_ip = $row['producto_ip'];

// Procesar el cambio de estado del producto
if ($nuevo_estado === 'deshabilitado') {
    // Deshabilitar el producto en el firewall del router
    $command = "/ip firewall address-list add list=BLOCKED_USERS address=$producto_ip";
    log_event("Cliente $producto_id ($producto_ip) deshabilitado.", LOG_PATH . 'router_connections.log');
} else {
    // Deshabilitar el producto en el firewall del router
    $command = "/ip firewall address-list remove [find address=$producto_ip]";
    log_event("Cliente $producto_id ($producto_ip) habilitado.", LOG_PATH . 'router_connections.log');
}

// Obtener la información del router desde la base de datos
$query_router = "SELECT categoria_ip, categoria_puerto, categoria_nombre FROM categoria WHERE categoria_id = (SELECT categoria_id FROM producto WHERE producto_id = ?)";
$stmt_router = $mysqli->prepare($query_router);
if ($stmt_router === false) {
    log_event("Error en la preparación de la consulta del router: " . $mysqli->error, LOG_PATH . 'router_connections.log');
    exit(1);
}
$stmt_router->bind_param('i', $producto_id);
$stmt_router->execute();
$result_router = $stmt_router->get_result();

if ($result_router->num_rows === 0) {
    log_event("No se encontró el router para el producto con ID $producto_id", LOG_PATH . 'router_connections.log');
    exit(1);
}

$row_router = $result_router->fetch_assoc();
$router_ip = $row_router['categoria_ip'];
$router_port = $row_router['categoria_puerto'];
$router_name = $row_router['categoria_nombre'];

// Conectar al router MikroTik
$ssh = new SSH2($router_ip, $router_port);
if (!$ssh->login('frabe', 'Fr4b3c0rp#12')) {
    log_event("Error de autenticación SSH en el router $router_name ($router_ip)", LOG_PATH . 'router_connections.log');
    exit(1);
}

// Ejecutar el comando en el router
$output = $ssh->exec($command);

if ($output === false) {
    log_event("Error al ejecutar el comando para actualizar la IP $producto_ip en el router $router_name ($router_ip)", LOG_PATH . 'router_connections.log');
} else {
    log_event("Comando ejecutado correctamente para actualizar la IP $producto_ip en el router $router_name ($router_ip)", LOG_PATH . 'router_connections.log');
}

// Marcar el cambio como procesado
$update_query = "UPDATE producto_cambios_eventos SET procesado = 1 WHERE producto_id = ?";
$update_stmt = $mysqli->prepare($update_query);
if ($update_stmt === false) {
    log_event("Error en la preparación de la actualización: " . $mysqli->error, LOG_PATH . 'router_connections.log');
    exit(1);
}
$update_stmt->bind_param('i', $producto_id);
$update_stmt->execute();

log_event("Cambio de estado procesado para el cliente $producto_id.", LOG_PATH . 'router_connections.log');

$stmt->close();
$mysqli->close();
?>
