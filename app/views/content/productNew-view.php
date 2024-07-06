<div class="container is-fluid mb-6">
	<h1 class="title">Clientes</h1>
	<h2 class="subtitle"><i class="fas fa-box fa-fw"></i> &nbsp; Nuevo cliente</h2>
</div>

<div class="container pb-6 pt-6">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/productoAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_producto" value="registrar">

		<div class="columns" style="display: none;">
			<div class="column">
    			<label>Servicio</label><br>
    			<div class="select">
        			<select name="servicios_id">
            			<?php
                			// Agregar la primera opción con servicios_id igual a 1
                			echo '<option value="1">1 - Seleccione un servicio</option>';

                			// Obtener los datos de servicios de la base de datos
                			$datos_servicios = $insLogin->seleccionarDatos("Normal", "servicios", "*", 0);

                			$cc = 2; // Contador para los siguientes servicios
                			while ($campos_servicios = $datos_servicios->fetch()) {
                   			echo '<option value="'.$campos_servicios['servicios_id'].'">'.$cc.' - '.$campos_servicios['servicios_nombre'].'</option>';
                    		$cc++;
                			}
           				 ?>
        			</select>
    			</div>
			</div>
			<div class="column">
				<div class="control">
    				<label>Precio Mensual</label><br>
    				<input id="servicio_precio_mensual" class="input" type="text" name="servicio_precio_mensual" readonly>
				</div>
			</div>
			<div class="column">
				<div class="control">
					<label>La facturación empieza</label>
					<input class="input" type="date" name="producto_fecha_facturacion">
				</div>
			</div>
		</div>

		<p class="has-text-centered" style="font-size: 1.5em;">
            <strong>DATOS DEL CLIENTE</strong>
        </p>
		<br>
		<br>
		<div class="columns">
			<div class="column">
				<div class="control">
					<label>Código de cliente <?php echo CAMPO_OBLIGATORIO; ?></label>
					<input class="input" type="text" name="producto_codigo" pattern="[a-zA-Z0-9- ]{1,77}" maxlength="77" required >
				</div>
			</div>
			<div class="column">
		    	<div class="control">
					<label>Nombre <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="producto_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100" required >
				</div>
		  	</div>
			<div class="column">
                <div class="control">
                    <label>Apellidos <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="producto_apellidos" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100" required>
                </div>
            </div>
			<div class="column">
                <label>Organización <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                <div class="select">
                    <select name="producto_categoria" required>
                        <option value="" selected="">Seleccione una opción</option>
                        <?php
                            $datos_categorias = $insLogin->seleccionarDatos("Normal", "categoria", "*", 0);

                            $cc = 1;
                            while ($campos_categoria = $datos_categorias->fetch()) {
                                echo '<option value="'.$campos_categoria['categoria_id'].'">'.$cc.' - '.$campos_categoria['categoria_nombre'].'</option>';
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
                    <input class="input" type="text" name="producto_correo" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
                </div>
            </div>
			<div class="column">
                <div class="control">
                    <label>Teléfono <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="producto_telefono" pattern="[0-9()+]{8,20}" maxlength="100" required>
                </div>
            </div>
			<div class="column">
                <div class="control">
                    <label>Teléfono 2</label>
                    <input class="input" type="text" name="producto_telefono2" pattern="[0-9()+]{8,20}" maxlength="100">
                </div>
            </div>
			<div class="column">
				<div class="control">
					<label>Fecha de registro <?php echo CAMPO_OBLIGATORIO; ?></label>
					<input class="input" type="date" name="producto_fecha_registro" value="<?php echo date("Y-m-d"); ?>" required>
				</div>
			</div>
		</div>
		<div class="columns">
			<div class="column">
                <div class="control">
                    <label>Dirección completa <?php echo CAMPO_OBLIGATORIO; ?></label>
                    <input class="input" type="text" name="producto_direccion" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,70}" maxlength="100" required>
                </div>
            </div>
			<div class="column">
                <div class="control">
                    <label>Referencias</label>
                    <input class="input" type="text" name="producto_referencias" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
                </div>
            </div>
			<div class="column">
                <div class="control">
                    <label>CP</label>
                    <input class="input" type="text" name="producto_cp" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100">
                </div>
            </div>			
		</div>
		<p class="has-text-centered" style="font-size: 1.5em;">
            <strong>ATRIBUTOS PERSONALIZADOS</strong>
        </p>
		<br>
		<br>
		<div class="columns">
			<div class="column">
		    	<div class="control">
					<label>Poste</label>
				  	<input class="input" type="text" name="producto_poste" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100" >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Etiqueta</label>
				  	<input class="input" type="text" name="producto_etiqueta" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100" >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Nodo-Caja</label>
				  	<input class="input" type="text" name="producto_nodo" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100" >
				</div>
		  	</div>
			<div class="column">
		    	<div class="control">
					<label>Contrato</label>
					<input class="input" type="text" name="producto_contrato" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}" maxlength="100" >
				</div>
			</div>	
		</div>
		<div class="columns" style="display: none;">
			<div class="column">
				<label>Foto o imagen del producto</label><br>
				<div class="file is-small has-name">
				  	<label class="file-label">
				    	<input class="file-input" type="file" name="producto_foto" accept=".jpg, .png, .jpeg" >
				    	<span class="file-cta">
				      		<span class="file-label">Imagen</span>
				    	</span>
				    	<span class="file-name">JPG, JPEG, PNG. (MAX 5MB)</span>
				  	</label>
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