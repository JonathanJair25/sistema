<?php
// offController.php
require __DIR__ . '/../../config.php'; // Ruta relativa ajustada desde app/controllers/offController.php
require __DIR__ . '/../../vendor/autoload.php'; // Ruta relativa ajustada desde app/controllers/offController.php
require __DIR__ . '/../db_connection.php'; // Ruta relativa ajustada desde app/controllers/offController.php

// Usar la biblioteca de SSH2 de phpseclib
use phpseclib3\Net\SSH2;
use phpseclib3\Exception\UnableToConnectException;

// Rutas para los archivos de log
$failedIpsPath = LOG_PATH . 'failed_ips.txt';
$outputLogPath = LOG_PATH . 'output.log';

// Asegurarse de que el directorio de logs existe
if (!is_dir(dirname($failedIpsPath))) {
    mkdir(dirname($failedIpsPath), 0777, true);
}

// Inicializar archivo de salida
file_put_contents($outputLogPath, "Inicio del script: " . date('Y-m-d H:i:s') . "\n");

// Mantener el estado de los productos ya procesados
$processedProducts = [];

// Comenzar el bucle de verificación
while (true) {
    $logMessage = "Ejecutando ciclo de verificación: " . date('Y-m-d H:i:s') . "\n";
    file_put_contents($outputLogPath, $logMessage, FILE_APPEND);

    // Obtener los detalles del producto desde la base de datos
    $stmt = $db->query("SELECT producto_id, producto_nombre, producto_apellidos, producto_ip, producto_estado, categoria_id FROM producto");

    // Verificar si la conexión a la base de datos es válida
    if (!$db) {
        $logMessage = "Error al conectar a la base de datos\n";
        file_put_contents($outputLogPath, $logMessage, FILE_APPEND);
        exit;
    }

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($products)) {
        $logMessage = "No se encontraron productos\n";
        file_put_contents($outputLogPath, $logMessage, FILE_APPEND);
    }

    foreach ($products as $product) {
        $logMessage = "Producto encontrado: ID {$product['producto_id']}, Estado: {$product['producto_estado']}\n";
        file_put_contents($outputLogPath, $logMessage, FILE_APPEND);

        $productId = $product['producto_id'];

        // Verificar si el producto ya ha sido procesado
        if (isset($processedProducts[$productId])) {
            if ($processedProducts[$productId] === $product['producto_estado']) {
                continue;
            }
        }

        // Actualizar el estado procesado
        $processedProducts[$productId] = $product['producto_estado'];

        try {
            $ssh = new SSH2($product['producto_ip']);
            if (!$ssh->login(SSH_USER, SSH_PASS)) {
                throw new UnableToConnectException('No se pudo conectar al dispositivo.');
            }

            if (strtolower($product['producto_estado']) === 'deshabilitado') {
                $logMessage = "Procesando deshabilitación para: {$product['producto_nombre']}\n";
                file_put_contents($outputLogPath, $logMessage, FILE_APPEND);
                $command = 'flash set CATV_ENABLED 0'; // Deshabilitado
                $logMessage = "Ejecutando comando SSH: $command\n";
                file_put_contents($outputLogPath, $logMessage, FILE_APPEND);
                $output = $ssh->exec($command);
                $logMessage = "Comando ejecutado: $command\n";
                file_put_contents($outputLogPath, $logMessage, FILE_APPEND);
                $logMessage = "Output: $output\n";
                file_put_contents($outputLogPath, $logMessage, FILE_APPEND);

                echo "Comando SSH ejecutado para deshabilitar el producto {$product['producto_nombre']}\n";
            } elseif (strtolower($product['producto_estado']) === 'habilitado') {
                $logMessage = "Procesando habilitación para: {$product['producto_nombre']}\n";
                file_put_contents($outputLogPath, $logMessage, FILE_APPEND);
                $command = 'flash set CATV_ENABLED 1'; // Habilitado
                $logMessage = "Ejecutando comando SSH: $command\n";
                file_put_contents($outputLogPath, $logMessage, FILE_APPEND);
                $output = $ssh->exec($command);
                $logMessage = "Comando ejecutado: $command\n";
                file_put_contents($outputLogPath, $logMessage, FILE_APPEND);
                $logMessage = "Output: $output\n";
                file_put_contents($outputLogPath, $logMessage, FILE_APPEND);

                echo "Comando SSH ejecutado para habilitar el producto {$product['producto_nombre']}\n";
            }
        } catch (UnableToConnectException $e) {
            // Escribir en el archivo de errores si la conexión falla
            if (!file_exists($failedIpsPath)) {
                file_put_contents($failedIpsPath, "Fecha - Nombre - Apellido - IP - Categoria\n");
            }
            $failed_ips = fopen($failedIpsPath, 'a');
            $date = date('Y-m-d H:i:s');
            fwrite($failed_ips, "$date - {$product['producto_nombre']} {$product['producto_apellidos']} - {$product['producto_ip']} - Categoria ID: {$product['categoria_id']}\n");
            fclose($failed_ips);

            $logMessage = "Error al conectar al dispositivo del cliente: {$product['producto_ip']}\n";
            file_put_contents($outputLogPath, $logMessage, FILE_APPEND);
        }
    }

    // Esperar antes de volver a verificar
    sleep(WAIT_TIME);
}
?>
