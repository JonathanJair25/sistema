<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\servicioController;

	if(isset($_POST['modulo_servicio'])){

		$insServicio = new servicioController();

		if($_POST['modulo_servicio']=="registrar"){
			echo $insServicio->registrarservicioControlador();
		}

		if($_POST['modulo_servicio']=="eliminar"){
			echo $insServicio->eliminarservicioControlador();
		}

		if($_POST['modulo_servicio']=="actualizar"){
			echo $insServicio->actualizarservicioControlador();
		}
		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}