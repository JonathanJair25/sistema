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

        // Consulta los pagos asociados a este producto
        $pagos = $insLogin->seleccionarDatos("Normal", "venta", "*", "producto_id = " . $id);

        // Mostrar el nombre del producto
        echo "<span class='has-text-weight-bold' style='font-size: 2rem;'>" . $datos['producto_nombre'] . " " . $datos['producto_apellidos'] . "</span><br>";

        // Comienza la tabla con clases centradas
        echo "<div class='table-container'>";
        echo "<table class='table is-fullwidth is-bordered is-hoverable'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th class='has-text-centered'>Fecha de creación</th>";
        echo "<th class='has-text-centered'>Cantidad</th>";
        echo "<th class='has-text-centered'>Total Pagado</th>";
        echo "<th class='has-text-centered'>Cambio</th>";
        echo "<th class='has-text-centered'>Usuario ID</th>";
        echo "<th class='has-text-centered'>Opciones</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        if ($pagos->rowCount() > 0) {
            while ($pago = $pagos->fetch()) {
                // Asegurarse de que el producto_id coincide
                if ($pago['producto_id'] == $id) {
                    echo "<tr>";
                    echo "<td class='has-text-centered'>" . date("d-m-Y", strtotime($pago['venta_fecha'])) . " " . $pago['venta_hora'] . "</td>";
                    echo "<td class='has-text-centered'>" . $pago['venta_total'] . "</td>";
                    echo "<td class='has-text-centered'>" . $pago['venta_pagado'] . "</td>";
                    echo "<td class='has-text-centered'>" . $pago['venta_cambio'] . "</td>";
                    echo "<td class='has-text-centered'>" . $pago['usuario_id'] . "</td>";
                    echo "<td class='has-text-centered'>";

                    // Enlace para detalles de la venta
                    echo "<a href='" . APP_URL . "saleDetail/" . $pago['venta_codigo'] . "/' class='button is-link is-rounded is-small' title='Información de venta Nro. " . $pago['venta_codigo'] . "' >";
                    echo "<i class='fas fa-shopping-bag fa-fw'></i>";
                    echo "</a>";

                    // Formulario para eliminar venta
                    echo "<form class='FormularioAjax is-inline-block' action='" . APP_URL . "app/ajax/ventaAjax.php' method='POST' autocomplete='off'>";
                    echo "<input type='hidden' name='modulo_venta' value='eliminar_venta'>";
                    echo "<input type='hidden' name='venta_id' value='" . $pago['venta_id'] . "'>";
                    echo "<button type='submit' class='button is-danger is-rounded is-small' title='Eliminar venta Nro. " . $pago['venta_id'] . "' >";
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
