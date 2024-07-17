<?php
	
	require_once "../../config/app.php";
	require_once "../views/inc/session_start.php";
	require_once "../../autoload.php";
	
	use app\controllers\entradaController;

	if(isset($_POST['modulo_entrada'])){

		$insEntrada = new entradaController();

		/*--------- Buscar producto por codigo ---------*/
		if($_POST['modulo_entrada']=="buscar_codigo"){
			echo $insEntrada->buscarCodigoEntradaControlador();
		}

		/*--------- Agregar producto a carrito ---------*/
		if($_POST['modulo_entrada']=="agregar_producto"){
			echo $insEntrada->agregarProductoCarritoControlador();
        }

        /*--------- Remover producto de carrito ---------*/
		if($_POST['modulo_entrada']=="remover_producto"){
			echo $insEntrada->removerProductoCarritoControlador();
		}

		/*--------- Actualizar producto de carrito ---------*/
		if($_POST['modulo_entrada']=="actualizar_producto"){
			echo $insEntrada->actualizarProductoCarritoControlador();
		}

		/*--------- Buscar cliente ---------*/
		if($_POST['modulo_entrada']=="buscar_cliente"){
			echo $insEntrada->buscarClienteEntradaControlador();
		}

		/*--------- Agregar cliente a carrito ---------*/
		if($_POST['modulo_entrada']=="agregar_cliente"){
			echo $insEntrada->agregarClienteEntradaControlador();
		}

		/*--------- Remover cliente de carrito ---------*/
		if($_POST['modulo_entrada']=="remover_cliente"){
			echo $insEntrada->removerClienteEntradaControlador();
		}

		/*--------- Registrar Entrada ---------*/
		if($_POST['modulo_entrada']=="registrar_entrada"){
			echo $insEntrada->registrarEntradaControlador();
		}

		/*--------- Eliminar Entrada ---------*/
		if($_POST['modulo_entrada']=="eliminar_entrada"){
			echo $insEntrada->eliminarEntradaControlador();
		}
		
	}else{
		session_destroy();
		header("Location: ".APP_URL."login/");
	}