<?php
// db_connection.php
require __DIR__ . '/../config.php'; // Ruta relativa ajustada desde app/db_connection.php

// Configuración de la base de datos
$host = DB_HOST;
$dbName = DB_NAME;
$username = DB_USER;
$password = DB_PASS;

try {
    $db = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    file_put_contents(LOG_PATH . 'failed_ips.txt', "Error de conexión a la base de datos: " . $e->getMessage() . "\n", FILE_APPEND);
    exit("Error de conexión a la base de datos: " . $e->getMessage());
}
?>
