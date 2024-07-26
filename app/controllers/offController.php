<?php
require __DIR__ . '/../../config.php'; // Ruta relativa ajustada desde app/controllers/offController.php
require __DIR__ . '/../../vendor/autoload.php'; // Ruta relativa ajustada desde app/controllers/offController.php
require __DIR__ . '/../db_connection.php'; // Ruta relativa ajustada desde app/controllers/offController.php

use phpseclib3\Net\SSH2;
use phpseclib3\Exception\UnableToConnectException;

define('BATCH_SIZE', 500);
define('LOG_LINE_LIMIT', 1000);

// Rutas para los archivos de log
$failedIpsPath = LOG_PATH . 'failed_ips.txt';
$outputLogPath = LOG_PATH . 'output.log';

// Asegurarse de que el directorio de logs existe
if (!is_dir(dirname($failedIpsPath))) {
    mkdir(dirname($failedIpsPath), 0777, true);
}

// Inicializar archivo de salida (borrando su contenido)
file_put_contents($outputLogPath, "Inicio del script: " . date('Y-m-d H:i:s') . "\n");

// Función para limitar el número de líneas en el log
function limitLogLines($filePath, $maxLines) {
    $lines = file($filePath, FILE_IGNORE_NEW_LINES);
    if (count($lines) > $maxLines) {
        $lines = array_slice($lines, -$maxLines);
        file_put_contents($filePath, implode("\n", $lines) . "\n");
    }
}

// Comenzar el bucle de verificación
while (true) {
    file_put_contents($outputLogPath, "Ejecutando ciclo de verificación: " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    limitLogLines($outputLogPath, LOG_LINE_LIMIT);

    // Obtener los detalles del cliente que han tenido cambios recientes
    $offset = 0;
    while (true) {
        $stmt = $db->prepare("SELECT producto_id, producto_nombre, producto_apellidos, producto_ip, producto_estado, categoria_id FROM producto WHERE estado_modificado > NOW() - INTERVAL :wait_time SECOND LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':wait_time', WAIT_TIME, PDO::PARAM_INT);
        $stmt->bindValue(':limit', BATCH_SIZE, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($clients)) {
            file_put_contents($outputLogPath, "No se encontraron clientes para el lote: $offset\n", FILE_APPEND);
            limitLogLines($outputLogPath, LOG_LINE_LIMIT);
            break;
        }

        foreach ($clients as $client) {
            $estado = strtolower(trim($client['producto_estado']));
            $ip = trim($client['producto_ip']);

            if (empty($estado)) {
                file_put_contents($outputLogPath, "Cliente encontrado con estado vacío: ID {$client['producto_id']}\n", FILE_APPEND);
                limitLogLines($outputLogPath, LOG_LINE_LIMIT);
                continue; // Saltar a la siguiente iteración
            }

            if (empty($ip)) {
                file_put_contents($outputLogPath, "Cliente encontrado con IP vacía: ID {$client['producto_id']}, Estado: {$estado}\n", FILE_APPEND);
                limitLogLines($outputLogPath, LOG_LINE_LIMIT);
                continue; // Saltar a la siguiente iteración
            }

            file_put_contents($outputLogPath, "Cliente encontrado: ID {$client['producto_id']}, Estado: {$estado}\n", FILE_APPEND);
            limitLogLines($outputLogPath, LOG_LINE_LIMIT);

            try {
                $ssh = new SSH2($ip);
                if (!$ssh->login(SSH_USER, SSH_PASS)) {
                    throw new UnableToConnectException('No se pudo conectar al dispositivo.');
                }

                if ($estado === 'deshabilitado') {
                    file_put_contents($outputLogPath, "Procesando deshabilitación para: {$client['producto_nombre']}\n", FILE_APPEND);
                    limitLogLines($outputLogPath, LOG_LINE_LIMIT);
                    $command = 'flash set CATV_ENABLED 0'; // Deshabilitado
                } elseif ($estado === 'habilitado') {
                    file_put_contents($outputLogPath, "Procesando habilitación para: {$client['producto_nombre']}\n", FILE_APPEND);
                    limitLogLines($outputLogPath, LOG_LINE_LIMIT);
                    $command = 'flash set CATV_ENABLED 1'; // Habilitado
                } else {
                    file_put_contents($outputLogPath, "Estado desconocido para el cliente: ID {$client['producto_id']}\n", FILE_APPEND);
                    limitLogLines($outputLogPath, LOG_LINE_LIMIT);
                    continue;
                }

                try {
                    file_put_contents($outputLogPath, "Ejecutando comando SSH: $command\n", FILE_APPEND);
                    $output = $ssh->exec($command);
                    file_put_contents($outputLogPath, "Comando ejecutado: $command\n", FILE_APPEND);
                    file_put_contents($outputLogPath, "Output: $output\n", FILE_APPEND);
                    limitLogLines($outputLogPath, LOG_LINE_LIMIT);

                    echo "Comando SSH ejecutado para el cliente {$client['producto_nombre']}\n";
                } catch (Exception $e) {
                    file_put_contents($outputLogPath, "Error al ejecutar el comando SSH: $command\n", FILE_APPEND);
                    file_put_contents($outputLogPath, "Mensaje de error: " . $e->getMessage() . "\n", FILE_APPEND);
                }
            } catch (UnableToConnectException $e) {
                if (!file_exists($failedIpsPath)) {
                    file_put_contents($failedIpsPath, "Fecha - Nombre - Apellido - IP - Categoria\n");
                }
                $date = date('Y-m-d H:i:s');
                file_put_contents($failedIpsPath, "$date - {$client['producto_nombre']} {$client['producto_apellidos']} - {$client['producto_ip']} - Categoria ID: {$client['categoria_id']}\n", FILE_APPEND);

                file_put_contents($outputLogPath, "Error al conectar al dispositivo del cliente: {$client['producto_ip']}\n", FILE_APPEND);
                limitLogLines($outputLogPath, LOG_LINE_LIMIT);
            } catch (Exception $e) {
                file_put_contents($outputLogPath, "Error en el cliente {$client['producto_nombre']} después de la conexión SSH\n", FILE_APPEND);
                file_put_contents($outputLogPath, "Mensaje de error: " . $e->getMessage() . "\n", FILE_APPEND);
                limitLogLines($outputLogPath, LOG_LINE_LIMIT);
            }
        }

        $offset += BATCH_SIZE;
    }

    // Esperar antes de volver a verificar
    sleep(WAIT_TIME);
}
?>
