<?php
// util.php

// Función para registrar mensajes en un archivo de log
function log_message($message, $file_path) {
    $max_lines = 1000;
    if (file_exists($file_path)) {
        $lines = file($file_path);
        if (count($lines) >= $max_lines) {
            $lines = array_slice($lines, -($max_lines - 1));
        }
    } else {
        $lines = [];
    }
    $lines[] = "[" . date('Y-m-d H:i:s') . "] " . $message . "\n";
    file_put_contents($file_path, implode('', $lines));
}

// Función para manejar el archivo de log
function manage_log_file($filePath) {
    $lines = [];
    if (file_exists($filePath)) {
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
    return $lines;
}
?>
