<?php
function manage_log_file($filePath) {
    if (!file_exists($filePath)) {
        touch($filePath); // Crea el archivo si no existe
    }
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return $lines;
}

function log_message($message, $file_path) {
    $lines = manage_log_file($file_path);
    $lines[] = $message;
    file_put_contents($file_path, implode(PHP_EOL, $lines) . PHP_EOL);
}

?>
