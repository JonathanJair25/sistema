<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require('../../../routeros-api-master/routeros_api.class.php');

// Configuración de la base de datos
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'sistemaredes';

// Conectar a la base de datos
$mysqli = new mysqli($host, $user, $password, $database);

if ($mysqli->connect_error) {
    die('Error de conexión a la base de datos: ' . $mysqli->connect_error);
}

// Obtener el producto_id desde la solicitud
$producto_id = isset($_GET['producto_id']) ? intval($_GET['producto_id']) : 0;

header('Content-Type: application/json'); // Aseguramos que el tipo de contenido sea JSON

if ($producto_id > 0) {
    // Buscar en la base de datos el producto correspondiente
    $query = "SELECT producto_ip, categoria_id FROM producto WHERE producto_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $producto_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $producto_ip = $row['producto_ip'];
        $categoria_id = $row['categoria_id'];

        // Buscar la IP del router en la tabla categoria
        $query = "SELECT categoria_ip FROM categoria WHERE categoria_id = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('i', $categoria_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $ip_router = $row['categoria_ip'];

            // Conectar a la API de MikroTik
            $API = new RouterosAPI();
            $usuario = 'frabe';
            $password = 'Fr4b3c0rp#12';
            $queue_target = $producto_ip . '/32'; // IP del target de la queue

            if ($API->connect($ip_router, $usuario, $password)) {
                // Obtener información de la queue específica
                $API->write('/queue/simple/print', false);
                $API->write('?target=' . $queue_target, true);
                $queue = $API->read();

                if (!empty($queue)) {
                    $queue = $queue[0]; // Obtener la primera (y única) queue que coincide
                    
                    // Separar el rate en upload y download
                    $rate = explode('/', $queue['rate']);
                    $upload = isset($rate[0]) ? (int)$rate[0] : 0;
                    $download = isset($rate[1]) ? (int)$rate[1] : 0;
                    
                    $trafficData = [
                        'upload' => $upload, // Tasa de subida en bytes por segundo
                        'download' => $download, // Tasa de bajada en bytes por segundo
                        'name' => $queue['name']
                    ];
                } else {
                    $trafficData = ['error' => 'Queue not found'];
                }

                $API->disconnect();
            } else {
                $trafficData = ['error' => 'Unable to connect to MikroTik'];
            }
        } else {
            $trafficData = ['error' => 'Router not found'];
        }
    } else {
        $trafficData = ['error' => 'Product not found'];
    }
    
    $stmt->close();
} else {
    $trafficData = ['error' => 'Invalid product ID'];
}

$mysqli->close();

// Devolver los datos como JSON para el frontend
echo json_encode($trafficData);
?>
