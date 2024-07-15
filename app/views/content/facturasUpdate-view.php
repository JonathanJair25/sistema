<div class="container is-fluid mb-6">
	<h1 class="title">Facturas</h1>
	<h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar factura</h2>
</div>

<div class="container pb-6 pt-6">
	<?php
	
		include "./app/views/inc/btn_back.php";

		$id=$insLogin->limpiarCadena($url[1]);

		$datos=$insLogin->seleccionarDatos("Unico","facturas","facturas_id",$id);

		if($datos->rowCount()==1){
			$datos=$datos->fetch();
	?>

	<h2 class="title has-text-centered"><?php echo $datos['facturas_etiqueta']." - ".$datos['facturas_nombre']; ?></h2>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/facturasAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_facturas" value="actualizar">
		<input type="hidden" name="facturas_id" value="<?php echo $datos['facturas_id']; ?>">

		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombre <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="facturas_nombre" value="<?php echo $datos['facturas_nombre']; ?>" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Etiqueta <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="facturas_etiqueta" maxlength="70" value="<?php echo $datos['facturas_etiqueta']; ?>" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Precio <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="facturas_precio" value="<?php echo number_format($datos['facturas_precio'],2,'.',''); ?>" required >
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
	<?php
		}else{
			include "./app/views/inc/error_alert.php";
		}
	?>
</div>