<div class="container is-fluid mb-6">
    <h1 class="title">PAGOS</h1>
    <h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; PAGOS DEL CLIENTE</h2>
</div>

<div class="container pb-1 pt-1">
    <?php
        include "./app/views/inc/btn_back.php";

        // Obtén el producto_id desde la URL o de donde sea necesario
        $id = $insLogin->limpiarCadena($url[1]);
    
        // Consulta los datos del producto
        $datos = $insLogin->seleccionarDatos("Unico", "producto", "producto_id", $id);
    
        if ($datos->rowCount() == 1) {
            $datos = $datos->fetch();
    
            // Consulta el nombre y precio del servicio asociado al producto
            $servicio_id = $datos['servicios_id'];
            $servicio_nombre = '';
            $servicio_precio_mensual = '';
            $servicio_datos = $insLogin->seleccionarDatos("Unico", "servicios", "servicios_id", $servicio_id);
            if ($servicio_datos->rowCount() == 1) {
                $servicio_datos = $servicio_datos->fetch();
                $servicio_nombre = $servicio_datos['servicios_nombre'];
                $servicio_precio_mensual = $servicio_datos['servicios_precio_mensual'];
            }
    
            // Consulta los pagos asociados a este producto
            $pagos = $insLogin->seleccionarDatos("Normal", "venta_detalle", "*", "producto_id = " . $datos['producto_id']);
    
            // Comienza el formulario y la interfaz HTML
    ?>

    
    

      <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/productoAjax.php" method="POST" autocomplete="off">
    <input type="hidden" name="modulo_producto" value="actualizar">
    <input type="hidden" name="producto_id" value="<?php echo $datos['producto_id']; ?>">
    </form>

    <?php
        } else {
            include "./app/views/inc/error_alert.php";
        }
    ?>
</div>
