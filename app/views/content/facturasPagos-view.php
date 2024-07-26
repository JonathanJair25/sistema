<div class="container is-fluid mb-6">
    <h1 class="title">FACTURAS</h1>
    <h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; Facturas del cliente</h2>
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

        // Consulta los pagos asociados a este producto
        $pagos = $insLogin->seleccionarDatos("Normal", "entrada", "*", "producto_id = " . $id);

        // Mostrar el nombre del producto con el botón al lado
        echo "<div class='is-flex is-align-items-center'>";
        echo "<span class='has-text-weight-bold' style='font-size: 2rem;'>" . $datos['producto_nombre'] . " " . $datos['producto_apellidos'] . "</span>";
        echo "<a href='" . APP_URL . "entradaNew/" . $id . "/' class='button is-success is-rounded ml-4' title='Agregar Pago'>";
        echo "<i class='fas fa-plus fa-fw'></i> Agregar Factura";
        echo "</a>";
        echo "</div><br><br>";

        // Comienza la tabla con clases centradas
        echo "<div class='table-container'>";
        echo "<table class='table is-fullwidth is-bordered is-hoverable'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th class='has-text-centered'>Fecha de creación</th>";
        echo "<th class='has-text-centered'>Cantidad</th>";
        echo "<th class='has-text-centered'>Usuario</th>";
        echo "<th class='has-text-centered'>Opciones</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        if ($pagos->rowCount() > 0) {
            while ($pago = $pagos->fetch()) {
                // Asegurarse de que el producto_id coincide
                if ($pago['producto_id'] == $id) {
                    // Consulta el nombre del usuario
                    $usuario = $insLogin->seleccionarDatos("Unico", "usuario", "usuario_id", $pago['usuario_id']);
                    $usuario_nombre = ($usuario->rowCount() == 1) ? $usuario->fetch()['usuario_nombre'] : "Desconocido";

                    echo "<tr>";
                    echo "<td class='has-text-centered'>" . date("d-m-Y", strtotime($pago['entrada_fecha'])) . " " . $pago['entrada_hora'] . "</td>";
                    echo "<td class='has-text-centered'>" . $pago['entrada_total'] . "</td>";
                    echo "<td class='has-text-centered'>" . $usuario_nombre . "</td>";
                    echo "<td class='has-text-centered'>";

                     // Formulario para eliminar venta
                     echo "<form class='FormularioAjax is-inline-block' action='" . APP_URL . "app/ajax/entradaAjax.php' method='POST' autocomplete='off'>";
                     echo "<input type='hidden' name='modulo_entrada' value='eliminar_entrada'>";
                     echo "<input type='hidden' name='entrada_id' value='" . $pago['entrada_id'] . "'>";
                     echo "<button type='submit' class='button is-danger is-rounded is-small' title='Eliminar venta Nro. " . $pago['entrada_id'] . "' >";
                     echo "<i class='far fa-trash-alt fa-fw'></i>";
                     echo "</button>";
                     echo "</form>";
                     echo "</td>";
                     echo "</tr>";
                 }
             }
        } else {
            echo "<tr><td colspan='7' class='has-text-centered'>No se encontraron pagos para este producto.</td></tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        include "./app/views/inc/error_alert.php";
    }
    ?>
</div>
