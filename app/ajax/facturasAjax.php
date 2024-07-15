<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\facturasController;

	if(isset($_POST['modulo_facturas'])){

		$insFacturas = new facturasController();

		if($_POST['modulo_facturas']=="registrar"){
			echo $insFacturas->registrarFacturaControlador();
		}

		if($_POST['modulo_facturas']=="eliminar"){
			echo $insFacturas->eliminarFacturaControlador();
		}

		if($_POST['modulo_facturas']=="actualizar"){
			echo $insFacturas->actualizarFacturaControlador();
		}
		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}