<div class="container is-fluid mb-6">
	<h1 class="title">Servicios</h1>
	<h2 class="subtitle"><i class="fas fa-sync-alt"></i> &nbsp; Actualizar servicio</h2>
</div>

<div class="container pb-6 pt-6">
	<?php
	
		include "./app/views/inc/btn_back.php";

		$id=$insLogin->limpiarCadena($url[1]);

		$datos=$insLogin->seleccionarDatos("Unico","servicios","servicios_id",$id);

		if($datos->rowCount()==1){
			$datos=$datos->fetch();
	?>

	<h2 class="title has-text-centered"><?php echo $datos['servicios_nombre']." $".$datos['servicios_precio_mensual']; ?></h2>

	<form class="FormularioAjax" action="<?php echo APP_URL; ?>app/ajax/servicioAjax.php" method="POST" autocomplete="off" >

		<input type="hidden" name="modulo_servicio" value="actualizar">
		<input type="hidden" name="servicios_id" value="<?php echo $datos['servicios_id']; ?>">

        <div class="columns">
            <div class="column">
				<label>Organización<?php echo CAMPO_OBLIGATORIO; ?></label><br>
		    	<div class="select">
				  	<select name="servicios_categoria_id" >
				    	<?php
                            $datos_categorias=$insLogin->seleccionarDatos("Normal","categoria","*",0);

                            $cc=1;
                            while($campos_categoria=$datos_categorias->fetch()){
                            	if($campos_categoria['categoria_id']==$datos['categoria_id']){
                            		echo '<option value="'.$campos_categoria['categoria_id'].'" selected="" >'.$cc.' - '.$campos_categoria['categoria_nombre'].' (Actual)</option>';
                            	}else{
                                	echo '<option value="'.$campos_categoria['categoria_id'].'">'.$cc.' - '.$campos_categoria['categoria_nombre'].'</option>';
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
					<label>Nombre del servicio <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="servicios_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#+\-\/ ]{1,100}" maxlength="70" value="<?php echo $datos['servicios_nombre']; ?>" required >
				</div>
		  	</div>
            <div class="column">
		    	<div class="control">
					<label>Precio (Mensual) <?php echo CAMPO_OBLIGATORIO; ?></label>
				  	<input class="input" type="text" name="servicios_precio_mensual" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo $datos['servicios_precio_mensual']; ?>" required >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Velocidad de bajada (Mbps)</label>
				  	<input class="input" type="text" name="servicios_velocidad_bajada" pattern="[0-9]{1,5}" maxlength="5" value="<?php echo $datos['servicios_velocidad_bajada']; ?>" >
				</div>
		  	</div>
              <div class="column">
		    	<div class="control">
					<label>Velocidad de subida (Mbps)</label>
				  	<input class="input" type="text" name="servicios_velocidad_subida" pattern="[0-9]{1,5}" maxlength="5" value="<?php echo $datos['servicios_velocidad_subida']; ?>" >
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