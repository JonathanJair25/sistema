<?php

	namespace app\controllers;
	use app\models\mainModel;

	class productController extends mainModel{

		/*----------  Controlador registrar cliente  ----------*/
		public function registrarProductoControlador(){

			# Almacenando datos#
		    $codigo=$this->limpiarCadena($_POST['producto_codigo']);
		    $nombre=$this->limpiarCadena($_POST['producto_nombre']);
		    $apellidos=$this->limpiarCadena($_POST['producto_apellidos']);
		    $correo=$this->limpiarCadena($_POST['producto_correo']);
		    $telefono=$this->limpiarCadena($_POST['producto_telefono']);
		    $telefono2=$this->limpiarCadena($_POST['producto_telefono2']);
		    $fecha_registro=$this->limpiarCadena($_POST['producto_fecha_registro']);
		    $direccion=$this->limpiarCadena($_POST['producto_direccion']);
		    $referencias=$this->limpiarCadena($_POST['producto_referencias']);
		    $cp=$this->limpiarCadena($_POST['producto_cp']);

		    $poste=$this->limpiarCadena($_POST['producto_poste']);
		    $etiqueta=$this->limpiarCadena($_POST['producto_etiqueta']);
		    $nodo=$this->limpiarCadena($_POST['producto_nodo']);
		    $contrato=$this->limpiarCadena($_POST['producto_contrato']);

		    $servicios_id=$this->limpiarCadena($_POST['servicios_id']);
		    $precio_mensual=$this->limpiarCadena($_POST['servicio_precio_mensual']);
		    $fecha_facturacion=$this->limpiarCadena($_POST['producto_fecha_facturacion']);
		    $ip=$this->limpiarCadena($_POST['producto_ip']);

		    $categoria=$this->limpiarCadena($_POST['producto_categoria']);

		    # Verificando campos obligatorios #
            if($codigo=="" || $nombre=="" || $apellidos=="" || $categoria=="" || $telefono=="" || $direccion==""){
            	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            # Verificando integridad de los datos #
		    if($this->verificarDatos("[a-zA-Z0-9- ]{1,77}",$codigo)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El CODIGO DE CLIENTE no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}",$nombre)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El NOMBRE no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

			# Verificando categoria #
		    $check_categoria=$this->ejecutarConsulta("SELECT categoria_id FROM categoria WHERE categoria_id='$categoria'");
		    if($check_categoria->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"La organización seleccionada no existe en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

			# Comprobando codigo de cliente #
		    $check_codigo=$this->ejecutarConsulta("SELECT producto_codigo FROM producto WHERE producto_codigo='$codigo'");
		    if($check_codigo->rowCount()>=1){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El código de cliente que ha ingresado ya se encuentra registrado en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    # Comprobando nombre de cliente #
		    $check_nombre=$this->ejecutarConsulta("SELECT producto_nombre FROM producto WHERE producto_codigo='$codigo' AND producto_nombre='$nombre'");
		    if($check_nombre->rowCount()>=1){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Ya existe un cliente registrado con el mismo nombre y código de cliente",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    # Directorios de imagenes #
			$img_dir='../views/productos/';

			# Comprobar si se selecciono una imagen #
    		if($_FILES['producto_foto']['name']!="" && $_FILES['producto_foto']['size']>0){

    			# Creando directorio #
		        if(!file_exists($img_dir)){
		            if(!mkdir($img_dir,0777)){
		            	$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"Error al crear el directorio",
							"icono"=>"error"
						];
						return json_encode($alerta);
		                exit();
		            } 
		        }

		        # Verificando formato de imagenes #
		        if(mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/png"){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
		            exit();
		        }

		        # Verificando peso de imagen #
		        if(($_FILES['producto_foto']['size']/1024)>5120){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"La imagen que ha seleccionado supera el peso permitido",
						"icono"=>"error"
					];
					return json_encode($alerta);
		            exit();
		        }

		        # Nombre de la foto #
		        $foto=$codigo."_".rand(0,100);

		        # Extension de la imagen #
		        switch(mime_content_type($_FILES['producto_foto']['tmp_name'])){
		            case 'image/jpeg':
		                $foto=$foto.".jpg";
		            break;
		            case 'image/png':
		                $foto=$foto.".png";
		            break;
		        }

		        chmod($img_dir,0777);

		        # Moviendo imagen al directorio #
		        if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'],$img_dir.$foto)){
		        	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"No podemos subir la imagen al sistema en este momento",
						"icono"=>"error"
					];
					return json_encode($alerta);
		            exit();
		        }

    		}else{
    			$foto="";
    		}

    		$producto_datos_reg=[
				[
					"campo_nombre"=>"producto_codigo",
					"campo_marcador"=>":Codigo",
					"campo_valor"=>$codigo
				],
				[
					"campo_nombre"=>"producto_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$nombre
				],
				[
					"campo_nombre"=>"producto_apellidos",
					"campo_marcador"=>":Apellidos",
					"campo_valor"=>$apellidos
				],
				[
					"campo_nombre"=>"producto_correo",
					"campo_marcador"=>":Correo",
					"campo_valor"=>$correo
				],
				[
					"campo_nombre"=>"producto_telefono",
					"campo_marcador"=>":Telefono",
					"campo_valor"=>$telefono
				],
				[
					"campo_nombre"=>"producto_telefono2",
					"campo_marcador"=>":Telefono2",
					"campo_valor"=>$telefono2
				],
				[
					"campo_nombre"=>"producto_fecha_registro",
					"campo_marcador"=>":Fecha_Registro",
					"campo_valor"=>$fecha_registro
				],
				[
					"campo_nombre"=>"producto_direccion",
					"campo_marcador"=>":Direccion",
					"campo_valor"=>$direccion
				],
				[
					"campo_nombre"=>"producto_referencias",
					"campo_marcador"=>":Referencias",
					"campo_valor"=>$referencias
				],
				[
					"campo_nombre"=>"producto_cp",
					"campo_marcador"=>":Cp",
					"campo_valor"=>$cp
				],
				[
					"campo_nombre"=>"producto_poste",
					"campo_marcador"=>":Poste",
					"campo_valor"=>$poste
				],
				[
					"campo_nombre"=>"producto_etiqueta",
					"campo_marcador"=>":Etiqueta",
					"campo_valor"=>$etiqueta
				],
				[
					"campo_nombre"=>"producto_nodo",
					"campo_marcador"=>":Nodo",
					"campo_valor"=>$nodo
				],
				[
					"campo_nombre"=>"producto_contrato",
					"campo_marcador"=>":Contrato",
					"campo_valor"=>$contrato
				],
				[
					"campo_nombre"=>"servicios_id",
					"campo_marcador"=>":Servicios_Id",
					"campo_valor"=>$servicios_id
				],
				[
					"campo_nombre"=>"servicio_precio_mensual",
					"campo_marcador"=>":Precio_Mensual",
					"campo_valor"=>$precio_mensual
				],
				[
					"campo_nombre"=>"producto_fecha_facturacion",
					"campo_marcador"=>":Fecha_Facturacion",
					"campo_valor"=>$fecha_facturacion
				],
				[
					"campo_nombre"=>"producto_ip",
					"campo_marcador"=>":Producto_Ip",
					"campo_valor"=>$ip
				],
				[
					"campo_nombre"=>"producto_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>"Habilitado"
				],
				[
					"campo_nombre"=>"producto_foto",
					"campo_marcador"=>":Foto",
					"campo_valor"=>$foto
				],
				[
					"campo_nombre"=>"categoria_id",
					"campo_marcador"=>":Categoria",
					"campo_valor"=>$categoria
				]
			];

			$registrar_producto=$this->guardarDatos("producto",$producto_datos_reg);

			if($registrar_producto->rowCount()==1){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Cliente registrado",
					"texto"=>"El cliente ".$nombre." ".$apellidos." se registro con exito",
					"icono"=>"success"
				];
			}else{
				
				if(is_file($img_dir.$foto)){
		            chmod($img_dir.$foto,0777);
		            unlink($img_dir.$foto);
		        }

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el cliente, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}
	
		/----------  Controlador listar clientes  ----------/
		public function listarProductoControlador($pagina, $registros, $url, $busqueda, $categoria) {
			$pagina = $this->limpiarCadena($pagina);
			$registros = $this->limpiarCadena($registros);
			$categoria = $this->limpiarCadena($categoria);
			$url = $this->limpiarCadena($url);
		
			if ($categoria > 0) {
				$url = APP_URL . $url . "/" . $categoria . "/";
			} else {
				$url = APP_URL . $url . "/";
			}
		
			$busqueda = $this->limpiarCadena($busqueda);
			$tabla = "";
		
			$pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
			$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
		
			$campos = "producto.producto_id, producto.producto_codigo, producto.producto_nombre, producto.producto_foto, 
					   categoria.categoria_nombre, producto.producto_apellidos, producto.producto_correo, 
					   producto.producto_telefono, producto.producto_telefono2, producto.producto_fecha_registro, 
					   producto.producto_direccion, producto.producto_referencias, producto.producto_cp, 
					   producto.producto_poste, producto.producto_etiqueta, producto.producto_nodo, 
					   producto.producto_contrato, producto.servicios_id, servicios.servicios_nombre, 
					   servicios.servicios_precio_mensual, producto.servicio_precio_mensual, producto.producto_ip, 
					   producto.producto_fecha_facturacion, producto.producto_estado, producto.producto_credito";
		
			if (isset($busqueda) && $busqueda != "") {
				$consulta_datos = "SELECT $campos FROM producto 
								   INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id 
								   INNER JOIN servicios ON producto.servicios_id=servicios.servicios_id 
								   WHERE producto_codigo LIKE '%$busqueda%' 
								   OR producto_nombre LIKE '%$busqueda%'  
								   ORDER BY producto_id ASC 
								   LIMIT $inicio, $registros";
		
				$consulta_total = "SELECT COUNT(producto_id) FROM producto 
								   WHERE producto_codigo LIKE '%$busqueda%' 
								   OR producto_nombre LIKE '%$busqueda%'";
			} elseif ($categoria > 0) {
				$consulta_datos = "SELECT $campos FROM producto 
								   INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id 
								   INNER JOIN servicios ON producto.servicios_id=servicios.servicios_id 
								   WHERE producto.categoria_id='$categoria' 
								   ORDER BY producto.producto_id ASC 
								   LIMIT $inicio, $registros";
		
				$consulta_total = "SELECT COUNT(producto_id) FROM producto 
								   WHERE categoria_id='$categoria'";
			} else {
				$consulta_datos = "SELECT $campos FROM producto 
								   INNER JOIN categoria ON producto.categoria_id=categoria.categoria_id 
								   INNER JOIN servicios ON producto.servicios_id=servicios.servicios_id 
								   ORDER BY producto_id ASC 
								   LIMIT $inicio, $registros";
		
				$consulta_total = "SELECT COUNT(producto_id) FROM producto";
			}
		
			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();
		
			$total = $this->ejecutarConsulta($consulta_total);
			$total = (int) $total->fetchColumn();
		
			$numeroPaginas = ceil($total / $registros);
		
			if ($total >= 1 && $pagina <= $numeroPaginas) {
				$contador = $inicio + 1;
				$pag_inicio = $inicio + 1;
				foreach ($datos as $rows) {
					$fecha_registro = strftime("%d-%b-%Y", strtotime($rows['producto_fecha_registro']));
					$tabla .= '
					<article class="media pb-3 pt-3">
						<figure class="media-left">
							<p class="image is-64x64">';
					if (is_file("./app/views/productos/" . $rows['producto_foto'])) {
						$tabla .= '<img src="' . APP_URL . 'app/views/productos/' . $rows['producto_foto'] . '">';
					} else {
						$tabla .= '<img src="' . APP_URL . 'app/views/productos/default.png">';
					}
					$tabla .= '</p>
						</figure>
						<div class="media-content">
							<div class="columns">
								<div class="column">
									<p>
										<strong> ' . $rows['producto_codigo'] . ' - ' . $rows['producto_nombre'] . ' ' . $rows['producto_apellidos'] . '</strong><br> 
										<strong>ESTADO:</strong> ' . $rows['producto_estado'] . '<br>  
										<strong>DIRECCIÓN:</strong> ' . $rows['producto_direccion'] . '<br>
										<strong>TÉLEFONO:</strong> ' . $rows['producto_telefono'] . '<br>  
										<strong>FECHA DE REGISTRO:</strong> ' . $fecha_registro . '<br>
									</p>
								</div>
								<div class="column">
									<p>
										<strong>ORGANIZACIÓN:</strong> ' . $rows['categoria_nombre'] . '<br>
										<strong>ETIQUETA:</strong> ' . $rows['producto_etiqueta'] . '<br>
										<strong>POSTE:</strong> ' . $rows['producto_poste'] . '<br>
										<strong>NODO:</strong> ' . $rows['producto_nodo'] . '<br>
										<strong>CONTRATO:</strong> ' . $rows['producto_contrato'] . '<br>
										<strong>IP:</strong> ' . $rows['producto_ip'] . '
									</p>
								</div>
								<div class="column">
									<p>
										<strong>SERVICIO:</strong> <span class="servicio-nombre">' . $rows['servicios_nombre'] . '</span><br>   
										<strong>PRECIO MENSUAL:</strong> <span class="servicio-precio">' . $rows['servicio_precio_mensual'] . '</span><br>
										<br>
										<strong>SALDO DE LA CUENTA:</strong><br>
										<strong>CRÉDITO:</strong> ' . $rows['producto_credito'] . '<br>
										<strong>PENDIENTES:</strong> <br>
									</p>
								</div>
							</div>
							<div class="columns">
								<div class="column">
									<strong>VENTAS ASOCIADAS:</strong>
									<div class="table-container">
										<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
											<thead>
												<tr>
													<th class="has-text-centered">NRO.</th>
													<th class="has-text-centered">Codigo</th>
													<th class="has-text-centered">Fecha</th>
													<th class="has-text-centered">Cliente</th>
													<th class="has-text-centered">Vendedor</th>
													<th class="has-text-centered">Total</th>
													<th class="has-text-centered">Opciones</th>
												</tr>
											</thead>
											<tbody>';
		
					$ventas = $this->ejecutarConsulta("SELECT venta.venta_id, venta.venta_codigo, venta.venta_fecha, venta.venta_hora, venta.venta_total,
													  cliente.cliente_nombre, cliente.cliente_apellido, usuario.usuario_nombre, usuario.usuario_apellido
													  FROM venta 
													  INNER JOIN cliente ON venta.cliente_id = cliente.cliente_id 
													  INNER JOIN usuario ON venta.usuario_id = usuario.usuario_id 
													  WHERE venta.producto_id = " . $rows['producto_id']);
					$ventas = $ventas->fetchAll();
		
					foreach ($ventas as $venta) {
						$tabla .= '
												<tr class="has-text-centered">
													<td>' . $venta['venta_id'] . '</td>
													<td>' . $venta['venta_codigo'] . '</td>
													<td>' . date("d-m-Y", strtotime($venta['venta_fecha'])) . ' ' . $venta['venta_hora'] . '</td>
													<td>' . $this->limitarCadena($venta['cliente_nombre'] . ' ' . $venta['cliente_apellido'], 30, "...") . '</td>
													<td>' . $this->limitarCadena($venta['usuario_nombre'] . ' ' . $venta['usuario_apellido'], 30, "...") . '</td>
													<td>' . MONEDA_SIMBOLO . number_format($venta['venta_total'], MONEDA_DECIMALES, MONEDA_SEPARADOR_DECIMAL, MONEDA_SEPARADOR_MILLAR) . ' ' . MONEDA_NOMBRE . '</td>
													<td>
													                                                <button type="button" class="button is-link is-outlined is-rounded is-small btn-sale-options" onclick="print_invoice(\''.APP_URL.'app/pdf/invoice.php?code='.$venta['venta_codigo'].'\')" title="Imprimir factura Nro. '.$venta['venta_id'].'" >
                                                    <i class="fas fa-file-invoice-dollar fa-fw"></i>
                                                </button>

                                                <button type="button" class="button is-link is-outlined is-rounded is-small btn-sale-options" onclick="print_ticket(\''.APP_URL.'app/pdf/ticket.php?code='.$venta['venta_codigo'].'\')" title="Imprimir ticket Nro. '.$venta['venta_id'].'" >
                                                    <i class="fas fa-receipt fa-fw"></i>
                                                </button>

                                                <a href="'.APP_URL.'saleDetail/'.$venta['venta_codigo'].'/" class="button is-link is-rounded is-small" title="Informacion de venta Nro. '.$venta['venta_id'].'" >
                                                    <i class="fas fa-shopping-bag fa-fw"></i>
                                                </a>

                                                <form class="FormularioAjax is-inline-block" action="'.APP_URL.'app/ajax/ventaAjax.php" method="POST" autocomplete="off">
                                                    <input type="hidden" name="modulo_venta" value="eliminar_venta">
                                                    <input type="hidden" name="venta_id" value="'.$venta['venta_id'].'">
                                                    <button type="submit" class="button is-danger is-rounded is-small" title="Eliminar venta Nro. '.$venta['venta_id'].'" >
                                                        <i class="far fa-trash-alt fa-fw"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                ';
            }

            $tabla .= '
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            <hr>';
            $contador++;
        }
    } else {
        if ($total >= 1) {
            $tabla .= '
            <tr class="has-text-centered" >
                <td colspan="9">
                    <a href="' . $url . '" class="button is-link is-rounded is-small mt-2 mb-2">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>';
        } else {
            $tabla .= '
            <tr class="has-text-centered" >
                <td colspan="9">
                    No hay registros en el sistema
                </td>
            </tr>';
        }
    }

    $tabla .= '
            </tbody>
        </table>
    </div>';

    if ($total >= 1 && $pagina <= $numeroPaginas) {
        $tabla .= '<p class="has-text-right">Mostrando productos ' . $pag_inicio . ' al ' . ($pag_inicio + count($datos) - 1) . ' de un total de ' . $total . '</p>';
        $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
    }

    return $tabla;
}
		
		

		

		/*----------  Controlador eliminar cliente  ----------*/
		public function eliminarProductoControlador(){

			$id=$this->limpiarCadena($_POST['producto_id']);

			# Verificando cliente #
		    $datos=$this->ejecutarConsulta("SELECT * FROM producto WHERE producto_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el cliente en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    # Verificando ventas #
		    $check_ventas=$this->ejecutarConsulta("SELECT producto_id FROM venta_detalle WHERE producto_id='$id' LIMIT 1");
		    if($check_ventas->rowCount()>0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No podemos eliminar el cliente del sistema ya que tiene ventas asociadas",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    $eliminarProducto=$this->eliminarRegistro("producto","producto_id",$id);

		    if($eliminarProducto->rowCount()==1){

		    	if(is_file("../views/productos/".$datos['producto_foto'])){
		            chmod("../views/productos/".$datos['producto_foto'],0777);
		            unlink("../views/productos/".$datos['producto_foto']);
		        }

		        $alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Cliente eliminado",
					"texto"=>"El cliente ".$datos['producto_nombre']." ".$datos['producto_apellidos']." ha sido eliminado del sistema correctamente",
					"icono"=>"success"
				];

		    }else{
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido eliminar el cliente ".$datos['producto_nombre']." ".$datos['producto_apellidos']." del sistema, por favor intente nuevamente",
					"icono"=>"error"
				];
		    }

		    return json_encode($alerta);
		}


		/*----------  Controlador actualizar cliente  ----------*/
		public function actualizarProductoControlador(){

			$id=$this->limpiarCadena($_POST['producto_id']);

			# Verificando cliente #
		    $datos=$this->ejecutarConsulta("SELECT * FROM producto WHERE producto_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el cliente en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    # Almacenando datos#
		    $codigo=$this->limpiarCadena($_POST['producto_codigo']);
		    $nombre=$this->limpiarCadena($_POST['producto_nombre']);
			$apellidos=$this->limpiarCadena($_POST['producto_apellidos']);
		    $correo=$this->limpiarCadena($_POST['producto_correo']);
		    $telefono=$this->limpiarCadena($_POST['producto_telefono']);
		    $telefono2=$this->limpiarCadena($_POST['producto_telefono2']);
		    $fecha_registro=$this->limpiarCadena($_POST['producto_fecha_registro']);
		    $direccion=$this->limpiarCadena($_POST['producto_direccion']);
		    $referencias=$this->limpiarCadena($_POST['producto_referencias']);
		    $cp=$this->limpiarCadena($_POST['producto_cp']);

		    $poste=$this->limpiarCadena($_POST['producto_poste']);
		    $etiqueta=$this->limpiarCadena($_POST['producto_etiqueta']);
		    $nodo=$this->limpiarCadena($_POST['producto_nodo']);
		    $contrato=$this->limpiarCadena($_POST['producto_contrato']);

			$servicios_id=$this->limpiarCadena($_POST['servicios_id']);
		    $precio_mensual=$this->limpiarCadena($_POST['servicio_precio_mensual']);
		    $fecha_facturacion=$this->limpiarCadena($_POST['producto_fecha_facturacion']);
		    $ip=$this->limpiarCadena($_POST['producto_ip']);

		    $categoria=$this->limpiarCadena($_POST['producto_categoria']);

		    # Verificando campos obligatorios #
            if($codigo=="" || $nombre=="" || $apellidos=="" || $categoria=="" || $telefono=="" || $direccion==""){
            	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

            # Verificando integridad de los datos #
		    if($this->verificarDatos("[a-zA-Z0-9- ]{1,77}",$codigo)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El CODIGO no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,100}",$nombre)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El NOMBRE no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

			# Verificando categoria #
			if($datos['categoria_id']!=$categoria){
			    $check_categoria=$this->ejecutarConsulta("SELECT categoria_id FROM categoria WHERE categoria_id='$categoria'");
			    if($check_categoria->rowCount()<=0){
			        $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"La organización seleccionada no existe en el sistema",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
			    }
			}

			# Comprobando codigo de cliente #
			if($datos['producto_codigo']!=$codigo){
			    $check_codigo=$this->ejecutarConsulta("SELECT producto_codigo FROM producto WHERE producto_codigo='$codigo'");
			    if($check_codigo->rowCount()>=1){
			        $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"El código de cliente que ha ingresado ya se encuentra registrado en el sistema",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
			    }
			}

		    # Comprobando nombre de cliente #
		    if($datos['producto_nombre']!=$nombre){
			    $check_nombre=$this->ejecutarConsulta("SELECT producto_nombre FROM producto WHERE producto_codigo='$codigo' AND producto_nombre='$nombre'");
			    if($check_nombre->rowCount()>=1){
			        $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Ya existe un cliente registrado con el mismo nombre y código de cliente",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
			    }
		    }


		    $producto_datos_up=[
				[
					"campo_nombre"=>"producto_codigo",
					"campo_marcador"=>":Codigo",
					"campo_valor"=>$codigo
				],
				[
					"campo_nombre"=>"producto_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$nombre
				],
				[
					"campo_nombre"=>"producto_apellidos",
					"campo_marcador"=>":Apellidos",
					"campo_valor"=>$apellidos
				],
				[
					"campo_nombre"=>"producto_correo",
					"campo_marcador"=>":Correo",
					"campo_valor"=>$correo
				],
				[
					"campo_nombre"=>"producto_telefono",
					"campo_marcador"=>":Telefono",
					"campo_valor"=>$telefono
				],
				[
					"campo_nombre"=>"producto_telefono2",
					"campo_marcador"=>":Telefono2",
					"campo_valor"=>$telefono2
				],
				[
					"campo_nombre"=>"producto_fecha_registro",
					"campo_marcador"=>":Fecha_Registro",
					"campo_valor"=>$fecha_registro
				],
				[
					"campo_nombre"=>"producto_direccion",
					"campo_marcador"=>":Direccion",
					"campo_valor"=>$direccion
				],
				[
					"campo_nombre"=>"producto_referencias",
					"campo_marcador"=>":Referencias",
					"campo_valor"=>$referencias
				],
				[
					"campo_nombre"=>"producto_cp",
					"campo_marcador"=>":Cp",
					"campo_valor"=>$cp
				],
				[
					"campo_nombre"=>"producto_poste",
					"campo_marcador"=>":Poste",
					"campo_valor"=>$poste
				],
				[
					"campo_nombre"=>"producto_etiqueta",
					"campo_marcador"=>":Etiqueta",
					"campo_valor"=>$etiqueta
				],
				[
					"campo_nombre"=>"producto_nodo",
					"campo_marcador"=>":Nodo",
					"campo_valor"=>$nodo
				],
				[
					"campo_nombre"=>"producto_contrato",
					"campo_marcador"=>":Contrato",
					"campo_valor"=>$contrato
				],
				[
					"campo_nombre"=>"servicios_id",
					"campo_marcador"=>":Servicios_Id",
					"campo_valor"=>$servicios_id
				],
				[
					"campo_nombre"=>"servicio_precio_mensual",
					"campo_marcador"=>":Precio_Mensual",
					"campo_valor"=>$precio_mensual
				],
				[
					"campo_nombre"=>"producto_fecha_facturacion",
					"campo_marcador"=>":Fecha_Facturacion",
					"campo_valor"=>$fecha_facturacion
				],
				[
					"campo_nombre"=>"producto_ip",
					"campo_marcador"=>":Producto_Ip",
					"campo_valor"=>$ip
				],
				[
					"campo_nombre"=>"producto_estado",
					"campo_marcador"=>":Estado",
					"campo_valor"=>"Habilitado"
				],
				[
					"campo_nombre"=>"categoria_id",
					"campo_marcador"=>":Categoria",
					"campo_valor"=>$categoria
				]
			];

			$condicion=[
				"condicion_campo"=>"producto_id",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];

			if($this->actualizarDatos("producto",$producto_datos_up,$condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Cliente actualizado",
					"texto"=>"Los datos del cliente ".$datos['producto_nombre']." ".$datos['producto_apellidos']." se actualizaron correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los datos del cliente ".$datos['producto_nombre']." ".$datos['producto_apellidos'].", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}


		/*----------  Controlador eliminar foto cliente  ----------*/
		public function eliminarFotoProductoControlador(){

			$id=$this->limpiarCadena($_POST['producto_id']);

			# Verificando cliente #
		    $datos=$this->ejecutarConsulta("SELECT * FROM producto WHERE producto_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el cliente en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    # Directorio de imagenes #
    		$img_dir="../views/productos/";

    		chmod($img_dir,0777);

    		if(is_file($img_dir.$datos['producto_foto'])){

		        chmod($img_dir.$datos['producto_foto'],0777);

		        if(!unlink($img_dir.$datos['producto_foto'])){
		            $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Error al intentar eliminar la foto del cliente, por favor intente nuevamente",
						"icono"=>"error"
					];
					return json_encode($alerta);
		        	exit();
		        }
		    }else{
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado la foto del cliente en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    $producto_datos_up=[
				[
					"campo_nombre"=>"producto_foto",
					"campo_marcador"=>":Foto",
					"campo_valor"=>""
				]
			];

			$condicion=[
				"condicion_campo"=>"producto_id",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];

			if($this->actualizarDatos("producto",$producto_datos_up,$condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Foto eliminada",
					"texto"=>"La foto del cliente ".$datos['producto_nombre']." ".$datos['producto_apellidos']." se elimino correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Foto eliminada",
					"texto"=>"No hemos podido actualizar algunos datos del cliente ".$datos['producto_nombre']." ".$datos['producto_apellidos'].", sin embargo la foto ha sido eliminada correctamente",
					"icono"=>"warning"
				];
			}

			return json_encode($alerta);
		}


		/*----------  Controlador actualizar foto cliente  ----------*/
		public function actualizarFotoProductoControlador(){

			$id=$this->limpiarCadena($_POST['producto_id']);

			# Verificando cliente #
		    $datos=$this->ejecutarConsulta("SELECT * FROM producto WHERE producto_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el cliente en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    # Directorio de imagenes #
    		$img_dir="../views/productos/";

    		# Comprobar si se selecciono una imagen #
    		if($_FILES['producto_foto']['name']=="" && $_FILES['producto_foto']['size']<=0){
    			$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No ha seleccionado una foto para el cliente",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
    		}

    		# Creando directorio #
	        if(!file_exists($img_dir)){
	            if(!mkdir($img_dir,0777)){
	                $alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Error al crear el directorio",
						"icono"=>"error"
					];
					return json_encode($alerta);
	                exit();
	            } 
	        }

	        # Verificando formato de imagenes #
	        if(mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/jpeg" && mime_content_type($_FILES['producto_foto']['tmp_name'])!="image/png"){
	            $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"La imagen que ha seleccionado es de un formato no permitido",
					"icono"=>"error"
				];
				return json_encode($alerta);
	            exit();
	        }

	        # Verificando peso de imagen #
	        if(($_FILES['producto_foto']['size']/1024)>5120){
	            $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"La imagen que ha seleccionado supera el peso permitido",
					"icono"=>"error"
				];
				return json_encode($alerta);
	            exit();
	        }

	        # Nombre de la foto #
	        if($datos['producto_foto']!=""){
		        $foto=explode(".", $datos['producto_foto']);
		        $foto=$foto[0];
	        }else{
	        	$foto=$datos['producto_codigo']."_".rand(0,100);
	        }
	        

	        # Extension de la imagen #
	        switch(mime_content_type($_FILES['producto_foto']['tmp_name'])){
	            case 'image/jpeg':
	                $foto=$foto.".jpg";
	            break;
	            case 'image/png':
	                $foto=$foto.".png";
	            break;
	        }

	        chmod($img_dir,0777);

	        # Moviendo imagen al directorio #
	        if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'],$img_dir.$foto)){
	            $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No podemos subir la imagen al sistema en este momento",
					"icono"=>"error"
				];
				return json_encode($alerta);
	            exit();
	        }

	        # Eliminando imagen anterior #
	        if(is_file($img_dir.$datos['producto_foto']) && $datos['producto_foto']!=$foto){
		        chmod($img_dir.$datos['producto_foto'], 0777);
		        unlink($img_dir.$datos['producto_foto']);
		    }

		    $producto_datos_up=[
				[
					"campo_nombre"=>"producto_foto",
					"campo_marcador"=>":Foto",
					"campo_valor"=>$foto
				]
			];

			$condicion=[
				"condicion_campo"=>"producto_id",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];

			if($this->actualizarDatos("producto",$producto_datos_up,$condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Foto actualizada",
					"texto"=>"La foto del cliente '".$datos['producto_nombre']."' '".$datos['producto_apellidos']."' se actualizo correctamente",
					"icono"=>"success"
				];
			}else{

				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Foto actualizada",
					"texto"=>"No hemos podido actualizar algunos datos del cliente ".$datos['producto_nombre']." ".$datos['producto_apellidos'].", sin embargo la foto ha sido actualizada",
					"icono"=>"warning"
				];
			}

			return json_encode($alerta);
		}
	}