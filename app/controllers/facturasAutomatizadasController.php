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
        SELECT p.producto_id, p.servicio_precio_mensual, p.servicios_id, s.servicios_nombre, s.servicios_precio_mensual,
               p.saldo_pendiente, p.saldo_cuenta, p.producto_credito
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

        // Preparar las sentencias SQL para inserciones y actualizaciones
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

        $updateProductoQuery = "
            UPDATE producto 
            SET saldo_pendiente = :saldo_pendiente, saldo_cuenta = :saldo_cuenta, producto_credito = :producto_credito
            WHERE producto_id = :producto_id
        ";
        $updateProducto = $conn->prepare($updateProductoQuery);

        // Procesar cada producto individualmente
        foreach ($productos as $producto) {
            $productoId = $producto['producto_id'];
            $precioMensual = $producto['servicios_precio_mensual'];
            $serviciosId = $producto['servicios_id'];
            $servicioNombre = $producto['servicios_nombre'];
            $saldoPendiente = $producto['saldo_pendiente'];
            $saldoCuenta = $producto['saldo_cuenta'];
            $productoCredito = $producto['producto_credito'];

            // Inicializar variables para los nuevos saldos
            $nuevoSaldoPendiente = $saldoPendiente;
            $nuevoSaldoCuenta = $saldoCuenta;
            $nuevoSaldoCredito = $productoCredito;

            // Calcular el nuevo saldo pendiente, saldo cuenta y crédito
            if ($productoCredito >= $precioMensual) {
                // El crédito es suficiente para cubrir la factura
                $nuevoSaldoCredito -= $precioMensual;
                $nuevoSaldoPendiente += 0; // El saldo pendiente no cambia si el crédito cubre la factura
                $nuevoSaldoCuenta -= $precioMensual;
            } else {
                // El crédito no es suficiente para cubrir la factura
                $nuevoSaldoPendiente += ($precioMensual - $productoCredito);
                $nuevoSaldoCredito = 0;
                $nuevoSaldoCuenta -= $precioMensual;
            }

            // Verificar si la factura ya existe en la tabla de facturas
            $stmtFacturas = $conn->prepare("SELECT facturas_id FROM facturas WHERE facturas_id = :facturas_id");
            $stmtFacturas->execute(['facturas_id' => $serviciosId]);
            $facturaExists = $stmtFacturas->fetchColumn();

            if (!$facturaExists) {
                // Insertar una nueva factura si no existe
                $insertFacturaQuery = "
                    INSERT INTO facturas (facturas_nombre, facturas_etiqueta, facturas_precio) 
                    VALUES (:facturas_nombre, :facturas_etiqueta, :facturas_precio)
                ";
                $insertFactura = $conn->prepare($insertFacturaQuery);
                $insertFactura->execute([
                    'facturas_nombre' => 'Nuevo Servicio', // Ajustar según sea necesario
                    'facturas_etiqueta' => $serviciosId,   // Ajustar según sea necesario
                    'facturas_precio' => $precioMensual,
                ]);

                // Obtener el ID de la factura recién insertada
                $facturasId = $conn->lastInsertId();
            } else {
                $facturasId = $serviciosId;
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
                'entrada_detalle_descripcion' => $servicioNombre, // Descripción personalizada
                'facturas_id' => $facturasId,
                'producto_id' => $productoId,
                'entrada_codigo' => $entradaCodigo,
            ]);

            // Actualizar el saldo pendiente, saldo cuenta y crédito en la tabla producto
            $updateProducto->execute([
                'saldo_pendiente' => $nuevoSaldoPendiente,
                'saldo_cuenta' => $nuevoSaldoCuenta,
                'producto_credito' => $nuevoSaldoCredito,
                'producto_id' => $productoId,
            ]);
        }

        // Mensaje de éxito
        echo "Facturas generadas y saldos actualizados exitosamente.";
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
