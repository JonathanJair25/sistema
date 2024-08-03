<?php
define('APP_URL', 'http://localhost/sistemaredes/');

if(!isset($_POST['search'])) exit('No se recibió el valor a buscar');

require_once 'conexion.php';

function search() {
    $mysqli = getConnexion();
    $search = trim($_POST['search']);
    
    if (empty($search)) {
        // Si el campo de búsqueda está vacío, no se deben mostrar resultados.
        exit;
    }
    
    $search = $mysqli->real_escape_string($search);
    $sql = "SELECT producto_nombre, producto_codigo 
            FROM producto 
            WHERE producto_nombre LIKE '%$search%' 
            OR producto_codigo LIKE '%$search%'";
    $res = $mysqli->query($sql);
    
    $found = false; // Variable para verificar si se encontraron resultados

    while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
        echo "<p><a href='" . APP_URL . "productUpdate/$row[producto_codigo]'>$row[producto_nombre] -- ID: $row[producto_codigo]</a></p>";
        $found = true; // Marcar que se encontró al menos un resultado
    }
    
    if (!$found) {
        echo "<p>No se encontraron resultados para la búsqueda '$search'.</p>";
    }
}

search();
?>
