<div class="container is-fluid mb-6">
    <h1 class="title">Pagos</h1>
    <h2 class="subtitle"><i class="fas fa-cash-register fa-fw"></i> &nbsp; Nuevo pago</h2>
</div>

<div class="container pb-6 pt-6">
<?php
include "./app/views/inc/btn_back.php";
// Obtén el producto_id desde la URL o de donde sea necesario
$id_pago = $insLogin->limpiarCadena($url[1]);

// Consulta los datos del producto
$datos = $insLogin->seleccionarDatos("Unico", "producto", "producto_id", $id_pago);

// Obtener los datos del producto
$producto = $datos->fetch();
$producto_nombre = $producto['producto_nombre'];
$producto_apellidos = $producto['producto_apellidos'];
$producto_credito = $producto['producto_credito'];
?>

<!-- Mostrar el nombre y apellidos del producto -->
<div>
    <span class="has-text-weight-bold" style="font-size: 2rem;">
        <?php echo htmlspecialchars($producto_nombre) . " " . htmlspecialchars($producto_apellidos); ?>
    </span>
</div>
<br>
<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/pagoAjax.php" method="POST" autocomplete="off">
    <input type="hidden" name="modulo_pago" value="registrar">

    <div class="columns">
        <div class="column" style="display: none;">
            <div class="control">
                <label>PRODUCTO</label><br>
                <input id="producto_id" class="input" type="text" name="producto_id" value="<?php echo htmlspecialchars($id_pago); ?>" required>
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Cantidad</label><br>
                <input id="pago_cantidad" class="input" type="text" name="pago_cantidad" value="<?php echo htmlspecialchars($producto_credito); ?>" required>
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Pago</label><br>
                <input id="pago_pago" class="input" type="text" name="pago_pago" oninput="calcularCambio()">
            </div>
        </div>
        <div class="column">
            <div class="control">
                <label>Cambio</label><br>
                <input id="pago_cambio" class="input" type="text" name="pago_cambio" readonly>
            </div>
        </div>
    </div>

    <div class="columns">
        <div class="column" style="display: none;">
            <div class="control">
                <label>Caja de ventas <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                <div class="select mb-5">
                    <select name="pago_caja">
                        <?php
                            $datos_cajas = $insLogin->seleccionarDatos("Normal", "caja", "*", 0);

                            while($campos_caja = $datos_cajas->fetch()){
                                if($campos_caja['caja_id'] == $_SESSION['caja']){
                                    echo '<option value="'.$campos_caja['caja_id'].'" selected="">Caja No.'.$campos_caja['caja_numero'].' - '.$campos_caja['caja_nombre'].' (Actual)</option>';
                                } else {
                                    echo '<option value="'.$campos_caja['caja_id'].'">Caja No.'.$campos_caja['caja_numero'].' - '.$campos_caja['caja_nombre'].'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="columns">
        <div class="column">
            <div class="control">
                <label>Método de pago <?php echo CAMPO_OBLIGATORIO; ?></label>
                <select class="input" name="pago_metodo" required>
                    <option value="">Seleccione un método</option>
                    <option value="efectivo">Efectivo</option>
                    <option value="transferencia_bancaria">Transferencia bancaria</option>
                    <option value="tarjeta_credito">Tarjeta de crédito</option>
                    <option value="tarjeta_debito">Tarjeta de débito</option>
                </select>
            </div>
        </div>
    </div>

    <div class="columns">
        <div class="column">
            <div class="control">
                <label>Fecha</label>
                <input class="input" type="date" value="<?php echo date("Y-m-d"); ?>">
            </div>
        </div>
    </div>

    <div class="columns">
        <div class="column">
            <div class="control">
                <label>Notas <?php echo CAMPO_OBLIGATORIO; ?></label>
                <textarea class="input" name="pago_nota" rows="4" cols="50" placeholder="Ingrese sus notas aquí..." required></textarea>
            </div>
        </div>
    </div>

    <p class="has-text-centered">
        <button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
        <button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
    </p>

    <p class="has-text-centered pt-6">
        <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
    </p>
</form>
</div>

<script>
function calcularCambio() {
    var cantidad = parseFloat(document.getElementById('pago_cantidad').value) || 0;
    var pago = parseFloat(document.getElementById('pago_pago').value) || 0;
    var cambio = pago - cantidad;
    // Muestra 0.00 si el resultado es negativo, de lo contrario formatea el cambio con dos decimales
    document.getElementById('pago_cambio').value = (cambio < 0 ? 0 : cambio).toFixed(2);
}
</script>

