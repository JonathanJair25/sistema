<?php
require __DIR__ . '/../../config.php';
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../db_connection.php';
require __DIR__ . '/../util.php';

use phpseclib3\Net\SSH2;
use phpseclib3\Exception\UnableToConnectException;

$redis = new Predis\Client();

$log_file = LOG_PATH . 'offcontroller_log.txt';
$failed_log = LOG_PATH . 'failed_ips.txt';
$max_parallel_processes = 10; // Ajusta este valor según la capacidad de tu servidor
$current_processes = [];

while (true) {
    // Esperar hasta que haya espacio en el pool de procesos
    while (count($current_processes) >= $max_parallel_processes) {
        foreach ($current_processes as $key => $process) {
            $status = proc_get_status($process);
            if (!$status['running']) {
                proc_close($process);
                unset($current_processes[$key]);
            }
        }
        sleep(1); // Ajusta este tiempo según sea necesario
    }

    $item = $redis->lpop('estado_actualizado');

    if ($item) {
        $data = json_decode($item, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $producto_id = isset($data['producto_id']) ? $data['producto_id'] : null;
            $nuevo_estado = isset($data['nuevo_estado']) ? $data['nuevo_estado'] : null;

            if ($producto_id !== null && $nuevo_estado !== null) {
                $cmd = 'php process_ssh.php ' . escapeshellarg($producto_id) . ' ' . escapeshellarg($nuevo_estado);
                $process = proc_open($cmd, [], $pipes);

                if (is_resource($process)) {
                    $current_processes[] = $process;
                }
            } else {
                $lines = manage_log_file($failed_log);
                $lines[] = "[" . date('Y-m-d H:i:s') . "] Datos incompletos en el mensaje de Redis: producto_id o nuevo_estado no definidos.";
                file_put_contents($failed_log, implode(PHP_EOL, $lines) . PHP_EOL);
            }
        } else {
            $lines = manage_log_file($failed_log);
            $lines[] = "[" . date('Y-m-d H:i:s') . "] Error de decodificación JSON para producto_id: $data[producto_id] - Error: " . json_last_error_msg();
            file_put_contents($failed_log, implode(PHP_EOL, $lines) . PHP_EOL);
        }
    }

    sleep(WAIT_TIME);
}
?>
