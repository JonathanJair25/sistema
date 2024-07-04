<div class="container is-fluid mb-6">
	<h1 class="title">SERVICIOS</h1>
	<h2 class="subtitle"><i class="fas fa-cash-register fa-fw"></i> &nbsp; Nuevo servicio</h2>
</div>

<div class="container pb-6 pt-6">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/servicioAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_servicio" value="registrar">

		<div class="columns">
            <div class="column">
                <label>Organización <?php echo CAMPO_OBLIGATORIO; ?></label><br>
                <div class="select">
                    <select name="servicios_categoria_id" required>
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
					<label>Nombre del servicio <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="servicios_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#+\-\/ ]{1,100}" maxlength="70" required >
				</div>
		  	</div>
            <div class="column">
		    	<div class="control">
					<label>Precio (Mensual) <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="servicios_precio_mensual" pattern="[0-9.]{1,25}" maxlength="25" value="0.00" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Velocidad de bajada (Mbps)</label>
				  	<input class="input" type="text" name="servicios_velocidad_bajada" pattern="[0-9]{1,5}" maxlength="5" >
				</div>
		  	</div>
              <div class="column">
		    	<div class="control">
					<label>Velocidad de subida (Mbps)</label>
				  	<input class="input" type="text" name="servicios_velocidad_subida" pattern="[0-9]{1,5}" maxlength="5" >
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