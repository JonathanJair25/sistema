<div class="container is-fluid mb-6">
	<h1 class="title">Servicios</h1>
	<h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de servicios</h2>
</div>
<div class="container pb-6 pt-6">

	<div class="form-rest mb-6 mt-6"></div>

	<?php
		use app\controllers\servicioController;

		$insServicio = new servicioController();

		echo $insServicio->listarServicioControlador($url[1],15,$url[0],"");
	?>
</div>