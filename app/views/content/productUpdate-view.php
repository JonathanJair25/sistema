<div class="container is-fluid mb-6">
    <h1 class="title">CLIENTES</h1>
    <h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar cliente</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
        include "./app/views/inc/btn_back.php";

        $id = $insLogin->limpiarCadena($url[1]);
        $datos = $insLogin->seleccionarDatos("Unico", "producto", "producto_id", $id);

        if ($datos->rowCount() == 1) {
            $datos = $datos->fetch();

            // Consulta el nombre y precio del servicio
            $servicio_id = $datos['servicios_id'];
            $servicio_nombre = '';
            $servicio_precio_mensual = '';
            $servicio_datos = $insLogin->seleccionarDatos("Unico", "servicios", "servicios_id", $servicio_id);
            if ($servicio_datos->rowCount() == 1) {
                $servicio_datos = $servicio_datos->fetch();
                $servicio_nombre = $servicio_datos['servicios_nombre'];
                $servicio_precio_mensual = $servicio_datos['servicios_precio_mensual'];
            }
    ?>
    <div class="columns is-flex is-justify-content-center">
    	<figure class="full-width mb-3" style="max-width: 170px;">
    		<?php
    			if(is_file("./app/views/productos/".$datos['producto_foto'])){
    				echo '<img class="img-responsive" src="'.APP_URL.'app/views/productos/'.$datos['producto_foto'].'">';
    			}else{
    				echo '<img class="img-responsive" src="'.APP_URL.'app/views/productos/default.png">';
    			}
    		?>
		</figure>
  	</div>
    <h2 class="title has-text-centered"><?php echo $datos['producto_codigo']?></h2>
    <h2 class="title has-text-centered"><?php echo $datos['producto_nombre'] . " " . $datos['producto_apellidos']; ?></h2>
    <h2 class="title has-text-centered"><?php echo $servicio_nombre . ' - ' . $servicio_precio_mensual; ?></h2>


    <form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/productoAjax.php" method="POST" autocomplete="off">
        <input type="hidden" name="modulo_producto" value="actualizar">
        <input type="hidden" name="producto_id" value="<?php echo $datos['producto_id']; ?>">
        <br><br>

        <p class="has-text-centered" style="font-size: 1.5em;">
            <strong>FACTURACIÓN</strong>
        </p>
        <br><br>
        <div class="columns is-centered">
        <div class="column is-centered">
    <label>Servicio<?php echo CAMPO_OBLIGATORIO; ?></label><br>
    <div class="select">
        <select id="servicios_id" name="servicios_id">
            <?php
                $datos_servicios = $insLogin->seleccionarDatos("Normal", "servicios", "*", 0);
                $cc = 1;
                while ($campos_servicios = $datos_servicios->fetch()) {
                    $selected = ($campos_servicios['servicios_id'] == $datos['servicios_id']) ? 'selected' : '';
                    echo '<option value="' . $campos_servicios['servicios_id'] . '" data-precio="' . $campos_servicios['servicios_precio_mensual'] . '" ' . $selected . '>' . $cc . ' - ' . $campos_servicios['servicios_nombre'] . ' ' . ($selected ? '(Actual)' : '') . '</option>';
                    $cc++;
                }
            ?>
        </select>
    </div>
</div>
<div class="column">
            <div class="control">
                <label>Precio Mensual</label><br>
                <input id="servicios_precio_mensual" class="input" type="text" name="servicio_precio_mensual" value="<?php echo $datos['servicio_precio_mensual']; ?>" readonly>
            </div>
        </div>
    <div class="column is-centered">
        <div class="control">
            <label>La facturación empieza</label>
            <input class="input" type="date" name="producto_fecha_facturacion" value="<?php echo $datos['producto_fecha_facturacion']; ?>" required>
        </div>
    </div>
    </div>
    <p class="has-text-centered">
            <button type="submit" class="button is-success is-rounded"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar</button>
    </p>






        <br><br>
        <p class="has-text-centered" style="font-size: 1.5em;">
            <strong>DATOS DEL CLIENTE</strong>
        </p>
        <br><br>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Código de cliente <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="producto_codigo" value="<?php echo $datos['producto_codigo']; ?>" pattern="[a-zA-Z0-9- ]{1,77}" maxlength="77" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Nombre <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="producto_nombre" value="<?php echo $datos['producto_nombre']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Apellidos <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="producto_apellidos" value="<?php echo $datos['producto_apellidos']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100" required>
                </div>
            </div>
            <div class="column">
                <label>Organización<?php echo CAMPO_OBLIGATORIO; ?></label><br>
                <div class="select">
                    <select name="producto_categoria">
                        <?php
                            $datos_categorias = $insLogin->seleccionarDatos("Normal", "categoria", "*", 0);
                            $cc = 1;
                            while ($campos_categoria = $datos_categorias->fetch()) {
                                if ($campos_categoria['categoria_id'] == $datos['categoria_id']) {
                                    echo '<option value="' . $campos_categoria['categoria_id'] . '" selected="">' . $cc . ' - ' . $campos_categoria['categoria_nombre'] . ' (Actual)</option>';
                                } else {
                                    echo '<option value="' . $campos_categoria['categoria_id'] . '">' . $cc . ' - ' . $campos_categoria['categoria_nombre'] . '</option>';
                                }
                                $cc++;
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Correo</label>
                    <input class="input" type="text" name="producto_correo" value="<?php echo $datos['producto_correo']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Teléfono <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="producto_telefono" value="<?php echo $datos['producto_telefono']; ?>" pattern="[0-9()+]{8,20}" maxlength="100" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Teléfono 2</label>
                    <input class="input" type="text" name="producto_telefono2" value="<?php echo $datos['producto_telefono2']; ?>" pattern="[0-9()+]{8,20}" maxlength="100">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Fecha de registro <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="date" name="producto_fecha_registro" value="<?php echo $datos['producto_fecha_registro']; ?>" required>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Dirección completa <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="producto_direccion" value="<?php echo $datos['producto_direccion']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}" maxlength="100" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Referencias</label>
                    <input class="input" type="text" name="producto_referencias" value="<?php echo $datos['producto_referencias']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>CP</label>
                    <input class="input" type="text" name="producto_cp" value="<?php echo $datos['producto_cp']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
                </div>
            </div>            
        </div>
        <p class="has-text-centered" style="font-size: 1.5em;">
            <strong>ATRIBUTOS PERSONALIZADOS</strong>
        </p>
        <br><br>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Poste</label>
                    <input class="input" type="text" name="producto_poste" value="<?php echo $datos['producto_poste']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Etiqueta</label>
                    <input class="input" type="text" name="producto_etiqueta" value="<?php echo $datos['producto_etiqueta']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Nodo-Caja</label>
                    <input class="input" type="text" name="producto_nodo" value="<?php echo $datos['producto_nodo']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Contrato</label>
                    <input class="input" type="text" name="producto_contrato" value="<?php echo $datos['producto_contrato']; ?>" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
                </div>
            </div>    
        </div>
        <p class="has-text-centered">
            <button type="submit" class="button is-success is-rounded"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar</button>
        </p>
        <p class="has-text-centered pt-6">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
    </form>
    <script>
    document.getElementById('servicios_id').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var precioMensual = selectedOption.getAttribute('data-precio');
        document.getElementById('servicios_precio_mensual').value = precioMensual;
    });
</script>
    <?php
        } else {
            include "./app/views/inc/error_alert.php";
        }
    ?>
</div>
