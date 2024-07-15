<div class="container is-fluid mb-6">
	<h1 class="title">Facturas</h1>
	<h2 class="subtitle"><i class="fas fa-clipboard-list fa-fw"></i> &nbsp; Lista de facturas</h2>
</div>
<div class="container pb-6 pt-6">

	<div class="form-rest mb-6 mt-6"></div>

	<?php
		use app\controllers\facturasController;

		$insFacturas = new facturasController();

		echo $insFacturas->listarFacturasControlador($url[1],10,$url[0],"",0);
	?>
</div>