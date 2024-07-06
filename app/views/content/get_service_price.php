<?php
include "config.php";
include "conexion.php";

if (isset($_GET['servicios_id'])) {
    $servicios_id = intval($_GET['servicios_id']);
    $stmt = $conexion->prepare("SELECT servicios_precio_mensual FROM servicios WHERE servicios_id = :servicios_id LIMIT 1");
    $stmt->bindParam(':servicios_id', $servicios_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($servicio);
    } else {
        echo json_encode(['servicios_precio_mensual' => '']);
    }
} else {
    echo json_encode(['servicios_precio_mensual' => '']);
}
?>
