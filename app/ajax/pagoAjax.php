<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\pagoController;

	if(isset($_POST['modulo_pago'])){

		$insPago = new pagoController();

		if($_POST['modulo_pago']=="registrar"){
			echo $insPago->registrarPagoControlador();
		}

		if($_POST['modulo_pago']=="eliminar"){
			echo $insPago->eliminarPagoControlador();
		}

		if($_POST['modulo_pago']=="actualizar"){
			echo $insPago->actualizarPagoControlador();
		}
		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}