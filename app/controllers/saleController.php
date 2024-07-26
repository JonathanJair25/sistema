<?php

	namespace app\controllers;
	use app\models\mainModel;

	class saleController extends mainModel{

		/*---------- Controlador buscar codigo de producto ----------*/
        public function buscarCodigoVentaControlador(){

            /*== Recuperando codigo de busqueda ==*/
			$producto=$this->limpiarCadena($_POST['buscar_codigo']);

			/*== Comprobando que no este vacio el campo ==*/
			if($producto==""){
				return '
				<article class="message is-warning mt-4 mb-4">
					 <div class="message-header">
					    <p>¡Ocurrio un error inesperado!</p>
					 </div>
				    <div class="message-body has-text-centered">
				    	<i class="fas fa-exclamation-triangle fa-2x"></i><br>
						Debes de introducir el Nombre, Marca o Modelo del producto
				    </div>
				</article>';
				exit();
            }

            /*== Seleccionando productos en la DB ==*/
            $datos_productos=$this->ejecutarConsulta("SELECT * FROM producto WHERE (producto_nombre LIKE '%$producto%' OR producto_codigo LIKE '%$producto%') ORDER BY producto_nombre ASC");

            if($datos_productos->rowCount()>=1){

				$datos_productos=$datos_productos->fetchAll();

				$tabla='<div class="table-container mb-6"><table class="table is-striped is-narrow is-hoverable is-fullwidth"><tbody>';

				foreach($datos_productos as $rows){
					$tabla.='
					<tr class="has-text-left" >
                        <td><i class="fas fa-box fa-fw"></i> &nbsp; '.$rows['producto_nombre'].'</td>
                        <td class="has-text-centered">
                            <button type="button" class="button is-link is-rounded is-small" onclick="agregar_codigo(\''.$rows['producto_codigo'].'\')"><i class="fas fa-plus-circle"></i></button>
                        </td>
                    </tr>
                    ';
				}

				$tabla.='</tbody></table></div>';
				return $tabla;
			}else{
				return '<article class="message is-warning mt-4 mb-4">
					 <div class="message-header">
					    <p>¡Ocurrio un error inesperado!</p>
					 </div>
				    <div class="message-body has-text-centered">
				    	<i class="fas fa-exclamation-triangle fa-2x"></i><br>
						No hemos encontrado ningún producto en el sistema que coincida con <strong>“'.$producto.'”
				    </div>
				</article>';

				exit();
			}
        }


       /*---------- Controlador agregar producto a venta ----------*/
public function agregarProductoCarritoControlador(){

    /*== Recuperando codigo del producto ==*/
    $codigo = $this->limpiarCadena($_POST['producto_codigo']);

    if($codigo == ""){
        $alerta = [
            "tipo" => "simple",
            "titulo" => "Ocurrió un error inesperado",
            "texto" => "Debes de introducir el código de barras del producto",
            "icono" => "error"
        ];
        return json_encode($alerta);
        exit();
    }

    /*== Verificando integridad de los datos ==*/
    if($this->verificarDatos("[a-zA-Z0-9- ]{1,70}", $codigo)){
        $alerta = [
            "tipo" => "simple",
            "titulo" => "Ocurrió un error inesperado",
            "texto" => "El código de barras no coincide con el formato solicitado",
            "icono" => "error"
        ];
        return json_encode($alerta);
        exit();
    }

    /*== Comprobando producto en la DB ==*/
    $check_producto = $this->ejecutarConsulta("SELECT * FROM producto WHERE producto_codigo='$codigo'");
    if($check_producto->rowCount() <= 0){
        $alerta = [
            "tipo" => "simple",
            "titulo" => "Ocurrió un error inesperado",
            "texto" => "No hemos encontrado el producto con código de barras: '$codigo'",
            "icono" => "error"
        ];
        return json_encode($alerta);
        exit();
    } else {
        $campos = $check_producto->fetch();
    }

    /*== Codigo de producto ==*/
    $codigo = $campos['producto_codigo'];

    if(empty($_SESSION['datos_producto_venta'][$codigo])){

        $detalle_cantidad = 1;

        $detalle_total = $detalle_cantidad * $campos['servicio_precio_mensual'];
        $detalle_total = number_format($detalle_total, MONEDA_DECIMALES, '.', '');

        $_SESSION['datos_producto_venta'][$codigo] = [
            "producto_id" => $campos['producto_id'],
            "producto_codigo" => $campos['producto_codigo'],
            "venta_detalle_precio_venta" => $campos['servicio_precio_mensual'],
            "venta_detalle_cantidad" => 1,
            "venta_detalle_total" => $detalle_total,
            "venta_detalle_descripcion" => $campos['producto_nombre']
        ];

        $_SESSION['alerta_producto_agregado'] = "Se agregó <strong>".$campos['producto_nombre']."</strong> a la venta";
    } else {
        $detalle_cantidad = ($_SESSION['datos_producto_venta'][$codigo]['venta_detalle_cantidad']) + 1;
        $detalle_total = $detalle_cantidad * $campos['servicio_precio_mensual'];
        $detalle_total = number_format($detalle_total, MONEDA_DECIMALES, '.', '');

        $_SESSION['datos_producto_venta'][$codigo] = [
            "producto_id" => $campos['producto_id'],
            "producto_codigo" => $campos['producto_codigo'],
            "venta_detalle_precio_venta" => $campos['servicio_precio_mensual'],
            "venta_detalle_cantidad" => $detalle_cantidad,
            "venta_detalle_total" => $detalle_total,
            "venta_detalle_descripcion" => $campos['producto_nombre']
        ];

        $_SESSION['alerta_producto_agregado'] = "Se agregó +1 <strong>".$campos['producto_nombre']."</strong> para realizar un pago. Total en carrito: <strong>$detalle_cantidad</strong>";
    }

    $alerta = [
        "tipo" => "recargar",
        "titulo" => "Cliente Agregado",
        "texto" => "El cliente se ha agregado correctamente.",
        "icono" => "success"
    ];

    return json_encode($alerta);
}



        /*---------- Controlador remover producto de venta ----------*/
        public function removerProductoCarritoControlador(){

            /*== Recuperando codigo del producto ==*/
            $codigo=$this->limpiarCadena($_POST['producto_codigo']);

            unset($_SESSION['datos_producto_venta'][$codigo]);

            if(empty($_SESSION['datos_producto_venta'][$codigo])){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"¡Cliente removido!",
					"texto"=>"El cliente se ha removido del pago",
					"icono"=>"success"
				];
				
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido remover el producto, por favor intente nuevamente",
					"icono"=>"error"
				];
            }
            return json_encode($alerta);
        }


        /*---------- Controlador actualizar producto de venta ----------*/
        public function actualizarProductoCarritoControlador(){

            /*== Recuperando codigo & cantidad del producto ==*/
            $codigo=$this->limpiarCadena($_POST['producto_codigo']);
            $cantidad=$this->limpiarCadena($_POST['producto_cantidad']);

            /*== comprobando campos vacios ==*/
            if($codigo=="" || $cantidad==""){
            	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No podemos actualizar la cantidad de productos debido a que faltan algunos parámetros de configuración",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== comprobando cantidad de productos ==*/
            if($cantidad<=0){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Debes de introducir una cantidad mayor a 0",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== Comprobando producto en la DB ==*/
            $check_producto=$this->ejecutarConsulta("SELECT * FROM producto WHERE producto_codigo='$codigo'");
            if($check_producto->rowCount()<=0){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el producto con código de barras : '$codigo'",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }else{
                $campos=$check_producto->fetch();
            }

            /*== comprobando producto en carrito ==*/
            if(!empty($_SESSION['datos_producto_venta'][$codigo])){

                if($_SESSION['datos_producto_venta'][$codigo]["venta_detalle_cantidad"]==$cantidad){
                    $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"No has modificado la cantidad de productos",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
                }

                if($cantidad>$_SESSION['datos_producto_venta'][$codigo]["venta_detalle_cantidad"]){
                    $diferencia_productos="agrego +".($cantidad-$_SESSION['datos_producto_venta'][$codigo]["venta_detalle_cantidad"]);
                }else{
                    $diferencia_productos="quito -".($_SESSION['datos_producto_venta'][$codigo]["venta_detalle_cantidad"]-$cantidad);
                }

                $detalle_cantidad=$cantidad;

                $detalle_total=$detalle_cantidad*$campos['servicio_precio_mensual'];
                $detalle_total=number_format($detalle_total,MONEDA_DECIMALES,'.','');

                $_SESSION['datos_producto_venta'][$codigo]=[
                    "producto_id"=>$campos['producto_id'],
					"producto_codigo"=>$campos['producto_codigo'],
                    "venta_detalle_precio_venta"=>$campos['servicio_precio_mensual'],
                    "venta_detalle_cantidad"=>$detalle_cantidad,
                    "venta_detalle_total"=>$detalle_total,
                    "venta_detalle_descripcion"=>$campos['producto_nombre']
                ];

                $_SESSION['alerta_producto_agregado']="Se $diferencia_productos <strong>".$campos['producto_nombre']."</strong> a la venta. Total en carrito <strong>$detalle_cantidad</strong>";

                $alerta=[
					"tipo"=>"recargar",
                    "titulo"=>"Cantidad actualizada",
                    "texto"=>"Los Meses se actualizarón correctamente",
					"icono" => "success"
				];

				return json_encode($alerta);
            }else{
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el producto que desea actualizar en el carrito",
					"icono"=>"error"
				];
				return json_encode($alerta);
            }
        }


        /*---------- Controlador registrar venta ----------*/
        public function registrarVentaControlador(){

            $caja=$this->limpiarCadena($_POST['venta_caja']);
            $venta_pagado=$this->limpiarCadena($_POST['venta_abono']);

            /*== Comprobando integridad de los datos ==*/
            if($this->verificarDatos("[0-9.]{1,25}",$venta_pagado)){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El total pagado por el cliente no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            if($_SESSION['venta_total']<=0 || (!isset($_SESSION['datos_producto_venta']) && count($_SESSION['datos_producto_venta'])<=0)){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No ha agregado productos a esta venta",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }


            /*== Comprobando caja en la DB ==*/
            $check_caja=$this->ejecutarConsulta("SELECT * FROM caja WHERE caja_id='$caja'");
			if($check_caja->rowCount()<=0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"La caja no está registrada en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }else{
                $datos_caja=$check_caja->fetch();
            }


            /*== Formateando variables ==*/
            $venta_pagado=number_format($venta_pagado,MONEDA_DECIMALES,'.','');
            $venta_total=number_format($_SESSION['venta_total'],MONEDA_DECIMALES,'.','');

            $venta_fecha=date("Y-m-d");
            $venta_hora=date("h:i a");

            $venta_total_final=$venta_total;
            $venta_total_final=number_format($venta_total_final,MONEDA_DECIMALES,'.','');


            /*== Calculando el cambio ==*/
            if($venta_pagado<$venta_total_final){
                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Esta es una venta al contado, el total a pagar por el cliente no puede ser menor al total a pagar",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            $venta_cambio=$venta_pagado-$venta_total_final;
            $venta_cambio=number_format($venta_cambio,MONEDA_DECIMALES,'.','');


            /*== Calculando total en caja ==*/
            $movimiento_cantidad=$venta_pagado-$venta_cambio;
            $movimiento_cantidad=number_format($movimiento_cantidad,MONEDA_DECIMALES,'.','');

            $total_caja=$datos_caja['caja_efectivo']+$movimiento_cantidad;
            $total_caja=number_format($total_caja,MONEDA_DECIMALES,'.','');


            /*== Actualizando productos ==*/
            $errores_productos=0;
			foreach($_SESSION['datos_producto_venta'] as $productos){

                /*== Obteniendo datos del producto ==*/
                $check_producto=$this->ejecutarConsulta("SELECT * FROM producto WHERE producto_id='".$productos['producto_id']."' AND producto_codigo='".$productos['producto_codigo']."'");
                if($check_producto->rowCount()<1){
                    $errores_productos=1;
                    break;
                }else{
                    $datos_producto=$check_producto->fetch();
                }

                /*== Preparando datos para enviarlos al modelo ==*/

                $condicion=[
                    "condicion_campo"=>"producto_id",
                    "condicion_marcador"=>":ID",
                    "condicion_valor"=>$productos['producto_id']
                ];
			}

            /*== Reestableciendo DB debido a errores ==*/
            if($errores_productos==1){

                foreach($_SESSION['datos_producto_venta'] as $producto){

                    $condicion=[
                        "condicion_campo"=>"producto_id",
                        "condicion_marcador"=>":ID",
                        "condicion_valor"=>$producto['producto_id']
                    ];

                    $this->actualizarDatos("producto",$datos_producto_rs,$condicion);
                }

                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los productos en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            /*== generando codigo de venta ==*/
            $correlativo=$this->ejecutarConsulta("SELECT venta_id FROM venta");
			$correlativo=($correlativo->rowCount())+1;
            $codigo_venta=$this->generarCodigoAleatorio(10,$correlativo);

            /*== Preparando datos para enviarlos al modelo ==*/
			$datos_venta_reg=[
				[
					"campo_nombre"=>"venta_codigo",
					"campo_marcador"=>":Codigo",
					"campo_valor"=>$codigo_venta
				],
				[
					"campo_nombre"=>"venta_fecha",
					"campo_marcador"=>":Fecha",
					"campo_valor"=>$venta_fecha
				],
				[
					"campo_nombre"=>"venta_hora",
					"campo_marcador"=>":Hora",
					"campo_valor"=>$venta_hora
				],
				[
					"campo_nombre"=>"venta_total",
					"campo_marcador"=>":Total",
					"campo_valor"=>$venta_total_final
				],
				[
					"campo_nombre"=>"venta_pagado",
					"campo_marcador"=>":Pagado",
					"campo_valor"=>$venta_pagado
				],
				[
					"campo_nombre"=>"venta_cambio",
					"campo_marcador"=>":Cambio",
					"campo_valor"=>$venta_cambio
				],
				[
					"campo_nombre"=>"usuario_id",
					"campo_marcador"=>":Usuario",
					"campo_valor"=>$_SESSION['id']
				],
				[
					"campo_nombre"=>"producto_id",
					"campo_marcador"=>":ID",
					"campo_valor"=>$productos['producto_id']
				],
				[
					"campo_nombre"=>"caja_id",
					"campo_marcador"=>":Caja",
					"campo_valor"=>$caja
				]
            ];

            /*== Agregando venta ==*/
            $agregar_venta=$this->guardarDatos("venta",$datos_venta_reg);

            if($agregar_venta->rowCount()!=1){
                foreach($_SESSION['datos_producto_venta'] as $producto){

                    $condicion=[
                        "condicion_campo"=>"producto_id",
                        "condicion_marcador"=>":ID",
                        "condicion_valor"=>$producto['producto_id']
                    ];

                    $this->actualizarDatos("producto",$datos_producto_rs,$condicion);
                }

                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido registrar la venta, por favor intente nuevamente. Código de error: 001",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

           /*== Agregando detalles de la venta ==*/
$errores_venta_detalle=0;
foreach($_SESSION['datos_producto_venta'] as $venta_detalle){

    /*== Preparando datos para enviarlos al modelo ==*/
    $datos_venta_detalle_reg=[
        [
            "campo_nombre"=>"venta_detalle_cantidad",
            "campo_marcador"=>":Cantidad",
            "campo_valor"=>$venta_detalle['venta_detalle_cantidad']
        ],
        [
            "campo_nombre"=>"venta_detalle_precio_venta",
            "campo_marcador"=>":PrecioVenta",
            "campo_valor"=>$venta_detalle['venta_detalle_precio_venta']
        ],
        [
            "campo_nombre"=>"venta_detalle_total",
            "campo_marcador"=>":Total",
            "campo_valor"=>$venta_detalle['venta_detalle_total']
        ],
        [
            "campo_nombre"=>"venta_detalle_descripcion",
            "campo_marcador"=>":Descripcion",
            "campo_valor"=>$venta_detalle['venta_detalle_descripcion']
        ],
        [
            "campo_nombre"=>"venta_codigo",
            "campo_marcador"=>":VentaCodigo",
            "campo_valor"=>$codigo_venta
        ],
        [
            "campo_nombre"=>"producto_id",
            "campo_marcador"=>":Producto",
            "campo_valor"=>$venta_detalle['producto_id']
        ]
    ];

    $agregar_detalle_venta=$this->guardarDatos("venta_detalle",$datos_venta_detalle_reg);

    if($agregar_detalle_venta->rowCount()!=1){
        $errores_venta_detalle=1;
        break;
    }

    // Obtener el valor actual de producto_credito
    $producto_id = $venta_detalle['producto_id'];
    $check_producto_credito = $this->ejecutarConsulta("SELECT producto_credito FROM producto WHERE producto_id='$producto_id'");
    if($check_producto_credito->rowCount() == 1){
        $producto = $check_producto_credito->fetch();
        $producto_credito_actual = $producto['producto_credito'];

        // Sumar venta_total al producto_credito actual
        $nuevo_producto_credito = $producto_credito_actual + $venta_detalle['venta_detalle_total'];

        // Actualizar el campo producto_credito en la tabla producto
        $datos_producto_credito_up=[
            [
                "campo_nombre"=>"producto_credito",
                "campo_marcador"=>":Credito",
                "campo_valor"=>$nuevo_producto_credito
            ]
        ];

        $condicion_producto=[
            "condicion_campo"=>"producto_id",
            "condicion_marcador"=>":ID",
            "condicion_valor"=>$producto_id
        ];

        if(!$this->actualizarDatos("producto",$datos_producto_credito_up,$condicion_producto)){
            $errores_venta_detalle=1;
            break;
        }
    } else {
        $errores_venta_detalle=1;
        break;
    }
}

/*== Reestableciendo DB debido a errores ==*/
if($errores_venta_detalle==1){
    $this->eliminarRegistro("venta_detalle","venta_codigo",$codigo_venta);
    $this->eliminarRegistro("venta","venta_codigo",$codigo_venta);

    foreach($_SESSION['datos_producto_venta'] as $producto){

        $condicion=[
            "condicion_campo"=>"producto_id",
            "condicion_marcador"=>":ID",
            "condicion_valor"=>$producto['producto_id']
        ];

        $this->actualizarDatos("producto",$datos_producto_rs,$condicion);
    }

    $alerta=[
        "tipo"=>"simple",
        "titulo"=>"Ocurrió un error inesperado",
        "texto"=>"No hemos podido registrar la venta, por favor intente nuevamente. Código de error: 002",
        "icono"=>"error"
    ];
    return json_encode($alerta);
    exit();
}


            /*== Actualizando efectivo en caja ==*/
            $datos_caja_up=[
                [
					"campo_nombre"=>"caja_efectivo",
					"campo_marcador"=>":Efectivo",
					"campo_valor"=>$total_caja
				]
            ];

            $condicion_caja=[
                "condicion_campo"=>"caja_id",
                "condicion_marcador"=>":ID",
                "condicion_valor"=>$caja
            ];

            if(!$this->actualizarDatos("caja",$datos_caja_up,$condicion_caja)){

                $this->eliminarRegistro("venta_detalle","venta_codigo",$codigo_venta);
                $this->eliminarRegistro("venta","venta_codigo",$codigo_venta);

                foreach($_SESSION['datos_producto_venta'] as $producto){

                    $condicion=[
                        "condicion_campo"=>"producto_id",
                        "condicion_marcador"=>":ID",
                        "condicion_valor"=>$producto['producto_id']
                    ];

                    $this->actualizarDatos("producto",$datos_producto_rs,$condicion);
                }

                $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido registrar la venta, por favor intente nuevamente. Código de error: 003",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();

            }

            /*== Vaciando variables de sesion ==*/
            unset($_SESSION['venta_total']);
            unset($_SESSION['datos_cliente_venta']);
            unset($_SESSION['datos_producto_venta']);

            $_SESSION['venta_codigo_factura']=$codigo_venta;

            $alerta=[
				"tipo"=>"recargar",
				"titulo"=>"¡Pago registrado!",
				"texto"=>"El pago se registró con éxito en el sistema",
				"icono"=>"success"
			];
			return json_encode($alerta);
	        exit();
        }


        /*----------  Controlador listar venta  ----------*/
		public function listarVentaControlador($pagina, $registros, $url, $busqueda){

			$pagina = $this->limpiarCadena($pagina);
			$registros = $this->limpiarCadena($registros);
			$url = $this->limpiarCadena($url);
			$url = APP_URL . $url . "/";
			$busqueda = $this->limpiarCadena($busqueda);
			$tabla = "";
		
			$pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
			$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
		
			$campos_tablas = "venta.venta_id,venta.venta_codigo,venta.venta_fecha,venta.venta_hora,venta.venta_total,
							  venta.usuario_id,venta.caja_id,usuario.usuario_id,usuario.usuario_nombre,
							  usuario.usuario_apellido";
		
			if(isset($busqueda) && $busqueda != ""){
				$consulta_datos = "SELECT $campos_tablas 
								   FROM venta 
								   INNER JOIN usuario ON venta.usuario_id = usuario.usuario_id 
								   WHERE (venta.venta_codigo = '$busqueda') 
								   ORDER BY venta.venta_id DESC 
								   LIMIT $inicio, $registros";
		
				$consulta_total = "SELECT COUNT(venta_id) 
								   FROM venta 
								   WHERE (venta.venta_codigo = '$busqueda')";
			} else {
				$consulta_datos = "SELECT $campos_tablas 
								   FROM venta  
								   INNER JOIN usuario ON venta.usuario_id = usuario.usuario_id 
								   ORDER BY venta.venta_id DESC 
								   LIMIT $inicio, $registros";
		
				$consulta_total = "SELECT COUNT(venta_id) 
								   FROM venta";
			}
		
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
		
			$total = $this->ejecutarConsulta($consulta_total);
			$total = (int) $total->fetchColumn();
		
			$numeroPaginas = ceil($total / $registros);
		
			$tabla .= '
				<div class="table-container">
					<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
						<thead>
							<tr>
								<th class="has-text-centered">NRO.</th>
								<th class="has-text-centered">Codigo</th>
								<th class="has-text-centered">Fecha</th>
								<th class="has-text-centered">Vendedor</th>
								<th class="has-text-centered">Total</th>
								<th class="has-text-centered">Opciones</th>
							</tr>
						</thead>
						<tbody>
			';
		
			if($total >= 1 && $pagina <= $numeroPaginas){
				$contador = $inicio + 1;
				$pag_inicio = $inicio + 1;
				foreach($datos as $rows){
					$tabla .= '
						<tr class="has-text-centered">
							<td>'.$rows['venta_id'].'</td>
							<td>'.$rows['venta_codigo'].'</td>
							<td>'.date("d-m-Y", strtotime($rows['venta_fecha'])).' '.$rows['venta_hora'].'</td>
							<td>'.$this->limitarCadena($rows['usuario_nombre'].' '.$rows['usuario_apellido'], 30, "...").'</td>
							<td>'.MONEDA_SIMBOLO.number_format($rows['venta_total'], MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR).' '.MONEDA_NOMBRE.'</td>
							<td>
								<button type="button" class="button is-link is-outlined is-rounded is-small btn-sale-options" onclick="print_invoice(\''.APP_URL.'app/pdf/invoice.php?code='.$rows['venta_codigo'].'\')" title="Imprimir factura Nro. '.$rows['venta_id'].'" >
									<i class="fas fa-file-invoice-dollar fa-fw"></i>
								</button>
		
								<button type="button" class="button is-link is-outlined is-rounded is-small btn-sale-options" onclick="print_ticket(\''.APP_URL.'app/pdf/ticket.php?code='.$rows['venta_codigo'].'\')" title="Imprimir ticket Nro. '.$rows['venta_id'].'" >
									<i class="fas fa-receipt fa-fw"></i>
								</button>
		
								<a href="'.APP_URL.'saleDetail/'.$rows['venta_codigo'].'/" class="button is-link is-rounded is-small" title="Informacion de venta Nro. '.$rows['venta_id'].'" >
									<i class="fas fa-shopping-bag fa-fw"></i>
								</a>
		
								<form class="FormularioAjax is-inline-block" action="'.APP_URL.'app/ajax/ventaAjax.php" method="POST" autocomplete="off">
									<input type="hidden" name="modulo_venta" value="eliminar_venta">
									<input type="hidden" name="venta_id" value="'.$rows['venta_id'].'">
									<button type="submit" class="button is-danger is-rounded is-small" title="Eliminar venta Nro. '.$rows['venta_id'].'" >
										<i class="far fa-trash-alt fa-fw"></i>
									</button>
								</form>
							</td>
						</tr>
					';
					$contador++;
				}
				$pag_final = $contador - 1;
			} else {
				if($total >= 1){
					$tabla .= '
						<tr class="has-text-centered">
							<td colspan="7">
								<a href="'.$url.'1/" class="button is-link is-rounded is-small mt-4 mb-4">
									Haga clic acá para recargar el listado
								</a>
							</td>
						</tr>
					';
				} else {
					$tabla .= '
						<tr class="has-text-centered">
							<td colspan="7">
								No hay registros en el sistema
							</td>
						</tr>
					';
				}
			}
		
			$tabla .= '</tbody></table></div>';
		
			### Paginacion ###
			if($total > 0 && $pagina <= $numeroPaginas){
				$tabla .= '<p class="has-text-right">Mostrando ventas <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';
				$tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
			}
		
			return $tabla;
		}
		

	/*----------  Controlador eliminar venta  ----------*/
public function eliminarVentaControlador() {

    $id = $this->limpiarCadena($_POST['venta_id']);

    # Verificando venta #
    $datos = $this->ejecutarConsulta("SELECT * FROM venta WHERE venta_id='$id'");
    if ($datos->rowCount() <= 0) {
        $alerta = [
            "tipo" => "simple",
            "titulo" => "Ocurrió un error inesperado",
            "texto" => "No hemos encontrado la venta en el sistema",
            "icono" => "error"
        ];
        return json_encode($alerta);
        exit();
    } else {
        $datos = $datos->fetch();
    }

    # Verificando detalles de venta #
    $check_detalle_venta = $this->ejecutarConsulta("SELECT * FROM venta_detalle WHERE venta_codigo='" . $datos['venta_codigo'] . "'");
    $check_detalle_venta_count = $check_detalle_venta->rowCount();

    if ($check_detalle_venta_count > 0) {

        $errores_detalle = 0;
        while ($detalle = $check_detalle_venta->fetch()) {
            // Obtener el valor actual de producto_credito
            $producto_id = $detalle['producto_id'];
            $check_producto_credito = $this->ejecutarConsulta("SELECT producto_credito FROM producto WHERE producto_id='$producto_id'");
            if ($check_producto_credito->rowCount() == 1) {
                $producto = $check_producto_credito->fetch();
                $producto_credito_actual = $producto['producto_credito'];

                // Restar venta_total al producto_credito actual
                $nuevo_producto_credito = $producto_credito_actual - $detalle['venta_detalle_total'];

                // Actualizar el campo producto_credito en la tabla producto
                $datos_producto_credito_up = [
                    [
                        "campo_nombre" => "producto_credito",
                        "campo_marcador" => ":Credito",
                        "campo_valor" => $nuevo_producto_credito
                    ]
                ];

                $condicion_producto = [
                    "condicion_campo" => "producto_id",
                    "condicion_marcador" => ":ID",
                    "condicion_valor" => $producto_id
                ];

                if (!$this->actualizarDatos("producto", $datos_producto_credito_up, $condicion_producto)) {
                    $errores_detalle = 1;
                    break;
                }
            } else {
                $errores_detalle = 1;
                break;
            }
        }

        if ($errores_detalle == 1) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No hemos podido actualizar los créditos de los productos, por favor intente nuevamente",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        $eliminarVentaDetalle = $this->eliminarRegistro("venta_detalle", "venta_codigo", $datos['venta_codigo']);

        if ($eliminarVentaDetalle->rowCount() != $check_detalle_venta_count) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No hemos podido eliminar la venta del sistema, por favor intente nuevamente",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }
    }

    $eliminarVenta = $this->eliminarRegistro("venta", "venta_id", $id);

    if ($eliminarVenta->rowCount() == 1) {

        $alerta = [
            "tipo" => "recargar",
            "titulo" => "Venta eliminada",
            "texto" => "La venta ha sido eliminada del sistema correctamente",
            "icono" => "success"
        ];

    } else {
        $alerta = [
            "tipo" => "simple",
            "titulo" => "Ocurrió un error inesperado",
            "texto" => "No hemos podido eliminar la venta del sistema, por favor intente nuevamente",
            "icono" => "error"
        ];
    }

    return json_encode($alerta);
}
}



	 