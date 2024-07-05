<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\servicioController;

	if(isset($_POST['modulo_servicio'])){

		$insServicio = new servicioController();

		if($_POST['modulo_servicio']=="registrar"){
			echo $insServicio->registrarServicioControlador();
		}

		if($_POST['modulo_servicio']=="eliminar"){
			echo $insServicio->eliminarServicioControlador();
		}

		if($_POST['modulo_servicio']=="actualizar"){
			echo $insServicio->actualizarServicioControlador();
		}
		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}