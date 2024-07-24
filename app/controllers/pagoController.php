<?php

	namespace app\controllers;
	use app\models\mainModel;

	class pagoController extends mainModel{

		/*----------  Controlador registrar pago  ----------*/
		public function registrarPagoControlador(){

			# Almacenando datos#
		    $cantidad=$this->limpiarCadena($_POST['pago_cantidad']);
		    $pago=$this->limpiarCadena($_POST['pago_pago']);
		    $cambio=$this->limpiarCadena($_POST['pago_cambio']);
		    $metodo=$this->limpiarCadena($_POST['pago_metodo']);
		    $nota=$this->limpiarCadena($_POST['pago_nota']);
		    $caja=$this->limpiarCadena($_POST['pago_caja']);
		    $producto=$this->limpiarCadena($_POST['producto_id']);

		    # Verificando campos obligatorios #
		    if($metodo=="" || $cantidad==""){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

            /*== Formateando variables ==*/
            $fecha=date("Y-m-d");
            $hora=date("h:i a");


			$pago_datos_reg=[
				[
					"campo_nombre"=>"pago_cantidad",
					"campo_marcador"=>":Cantidad",
					"campo_valor"=>$cantidad
				],
				[
					"campo_nombre"=>"pago_pago",
					"campo_marcador"=>":Pago",
					"campo_valor"=>$pago
				],
                [
					"campo_nombre"=>"pago_cambio",
					"campo_marcador"=>":Cambio",
					"campo_valor"=>$cambio
				],
                [
					"campo_nombre"=>"pago_metodo",
					"campo_marcador"=>":Metodo",
					"campo_valor"=>$metodo
				],
                [
					"campo_nombre"=>"pago_fecha",
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$fecha
				],
				[
					"campo_nombre"=>"pago_hora",
					"campo_marcador"=>":Hora",
					"campo_valor"=>$hora
                ],
                [
					"campo_nombre"=>"pago_nota",
					"campo_marcador"=>":Nota",
					"campo_valor"=>$nota
                ],
                [
					"campo_nombre"=>"caja_id",
					"campo_marcador"=>":Caja",
					"campo_valor"=>$caja
				],
                [
					"campo_nombre"=>"producto_id",
					"campo_marcador"=>":ID",
					"campo_valor"=>$producto
				],
                [
					"campo_nombre"=>"usuario_id",
					"campo_marcador"=>":Usuario",
					"campo_valor"=>$_SESSION['id']
				]
			];

			$registrar_pago=$this->guardarDatos("pagos",$pago_datos_reg);

			if($registrar_pago->rowCount()==1){
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Pago registrado",
					"texto"=>"El pago se registro con exito",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el pago, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}
		}


		