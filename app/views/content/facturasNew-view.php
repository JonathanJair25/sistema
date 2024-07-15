<div class="container is-fluid mb-6">
	<h1 class="title">Facturas</h1>
	<h2 class="subtitle"><i class="fas fa-box fa-fw"></i> &nbsp; Nueva factura</h2>
</div>

<div class="container pb-6 pt-6">

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/facturasAjax.php" method="POST" autocomplete="off" enctype="multipart/form-data" >

		<input type="hidden" name="modulo_facturas" value="registrar">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="facturas_nombre" maxlength="100" required >
				</div>
		  	</div>
			  <div class="column">
			  	<div class="control">
					<label>Etiqueta</label>
				  	<input class="input" type="text" name="facturas_etiquetas" maxlength="77">
				</div>
		  	</div>
			  <div class="column">
		    	<div class="control">
					<label>Precio <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="facturas_precio" pattern="[0-9.]{1,25}" maxlength="25" value="0.00" required >
				</div>
		  	</div>
		</div>
	<br>
		<p class="has-text-centered">
			<button type="reset" class="button is-link is-light is-rounded"><i class="fas fa-paint-roller"></i> &nbsp; Limpiar</button>
			<button type="submit" class="button is-info is-rounded"><i class="far fa-save"></i> &nbsp; Guardar</button>
		</p>
		<p class="has-text-centered pt-6">
            <small>Los campos marcados con <?php echo CAMPO_OBLIGATORIO; ?> son obligatorios</small>
        </p>
	</form>
</div>