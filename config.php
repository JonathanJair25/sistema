<?php
// config.php

// Configuración de la base de datos
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_NAME')) define('DB_NAME', 'sistemaredes');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');

// Configuración de SSH
if (!defined('SSH_USER')) define('SSH_USER', 'root');
if (!defined('SSH_PASS')) define('SSH_PASS', 'root626');

// Configuración de rutas
if (!defined('LOG_PATH')) define('LOG_PATH', 'C:/xampp/htdocs/sistemaredes/logs/');

// Configuración de tiempo de espera
if (!defined('WAIT_TIME')) define('WAIT_TIME', 5);
?>
