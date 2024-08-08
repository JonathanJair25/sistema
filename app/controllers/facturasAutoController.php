<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistemaredes";

try {
    // Crear una nueva conexión PDO
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Definir la fecha y hora actuales
    $fechaActual = date('Y-m-d');
    $horaActual = date('H:i:s');

    // Definir el ID del usuario que está generando la factura (esto puede ser un parámetro dinámico)
    $usuarioGenerador = 1; // Cambiar según sea necesario

    // Consulta para obtener productos con servicio activo y detalles del servicio
    $query = "
        SELECT p.producto_id, p.servicio_precio_mensual, s.servicios_nombre, s.servicios_precio_mensual
        FROM producto p
        LEFT JOIN servicios s ON p.servicios_id = s.servicios_id
        WHERE p.servicios_id != 1 AND s.servicios_id IS NOT NULL
    ";

    // Preparar y ejecutar la consulta
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si hay productos para facturar
    if (count($productos) > 0) {

        // Preparar las sentencias SQL para inserciones
        $insertEntradaQuery = "
            INSERT INTO entrada (entrada_fecha, entrada_hora, entrada_total, usuario_id, facturas_id, producto_id, entrada_codigo) 
            VALUES (:entrada_fecha, :entrada_hora, :entrada_total, :usuario_id, :facturas_id, :producto_id, :entrada_codigo)
        ";
        $insertEntrada = $conn->prepare($insertEntradaQuery);

        $insertEntradaDetalleQuery = "
            INSERT INTO entrada_detalle (entrada_detalle_cantidad, entrada_detalle_precio_venta, entrada_detalle_total, entrada_detalle_descripcion, facturas_id, producto_id, entrada_codigo) 
            VALUES (:entrada_detalle_cantidad, :entrada_detalle_precio_venta, :entrada_detalle_total, :entrada_detalle_descripcion, :facturas_id, :producto_id, :entrada_codigo)
        ";
        $insertEntradaDetalle = $conn->prepare($insertEntradaDetalleQuery);

        // Preparar la consulta para verificar la existencia de la factura
        $stmtFacturas = $conn->prepare("
            SELECT facturas_id 
            FROM facturas 
            WHERE facturas_nombre = :facturas_nombre
        ");

        // Procesar cada producto individualmente
        foreach ($productos as $producto) {
            $productoId = $producto['producto_id'];
            $precioMensual = $producto['servicios_precio_mensual'];
            $servicioNombre = $producto['servicios_nombre'];
            
            // Verificar si la factura ya existe en la tabla de facturas
            $stmtFacturas->execute([
                'facturas_nombre' => $servicioNombre
            ]);
            $facturasId = $stmtFacturas->fetchColumn();

            if (!$facturasId) {
                // Insertar una nueva factura si no existe
                $insertFacturaQuery = "
                    INSERT INTO facturas (facturas_nombre, facturas_precio) 
                    VALUES (:facturas_nombre, :facturas_precio)
                ";
                $insertFactura = $conn->prepare($insertFacturaQuery);
                $insertFactura->execute([
                    'facturas_nombre' => $servicioNombre,
                    'facturas_precio' => $precioMensual,
                ]);

                // Obtener el ID de la factura recién insertada
                $facturasId = $conn->lastInsertId();
            }

            // Generar un código único para la entrada
            $entradaCodigo = uniqid();

            // Insertar la entrada en la tabla entrada
            $insertEntrada->execute([
                'entrada_fecha' => $fechaActual,
                'entrada_hora' => $horaActual,
                'entrada_total' => $precioMensual,
                'usuario_id' => $usuarioGenerador,
                'facturas_id' => $facturasId,
                'producto_id' => $productoId,
                'entrada_codigo' => $entradaCodigo,
            ]);

            // Insertar los detalles de la entrada en la tabla entrada_detalle
            $insertEntradaDetalle->execute([
                'entrada_detalle_cantidad' => 1,
                'entrada_detalle_precio_venta' => $precioMensual,
                'entrada_detalle_total' => $precioMensual,
                'entrada_detalle_descripcion' => $servicioNombre,
                'facturas_id' => $facturasId,
                'producto_id' => $productoId,
                'entrada_codigo' => $entradaCodigo,
            ]);
        }

        // Mensaje de éxito
        echo "Facturas generadas exitosamente.";
    } else {
        // Mensaje si no hay productos para facturar
        echo "No hay productos para facturar.";
    }

} catch(PDOException $e) {
    // Manejo de errores
    echo "Error: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
?>
