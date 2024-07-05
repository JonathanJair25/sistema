<?php

	namespace app\controllers;
	use app\models\mainModel;

	class servicioController extends mainModel{

		/*----------  Controlador registrar servicio  ----------*/
		public function registrarServicioControlador(){

			# Almacenando datos#
		    $nombre=$this->limpiarCadena($_POST['servicios_nombre']);
		    $precio_mensual=$this->limpiarCadena($_POST['servicios_precio_mensual']);
		    $velocidad_bajada=$this->limpiarCadena($_POST['servicios_velocidad_bajada']);
		    $velocidad_subida=$this->limpiarCadena($_POST['servicios_velocidad_subida']);
            $categoria_id=$this->limpiarCadena($_POST['servicios_categoria_id']);

		    # Verificando campos obligatorios #
		    if($precio_mensual=="" || $nombre=="" || $categoria_id==""){
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
		    if($this->verificarDatos("[0-9]{1,5}",$velocidad_bajada)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"LA VELOCIDAD DE BAJADA no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

            if($this->verificarDatos("[0-9]{1,5}",$velocidad_subida)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"LA VELOCIDAD DE SUBIDA no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#+\-\/ ]{1,100}",$nombre)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El NOMBRE DEL SERVICIO no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    if($this->verificarDatos("[0-9.]{1,25}",$precio_mensual)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El PRECIO DEL SERVICIO no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

            # Verificando categoria #
		    $check_categoria=$this->ejecutarConsulta("SELECT categoria_id FROM categoria WHERE categoria_id='$categoria_id'");
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

		    # Comprobando que el efectivo sea mayor o igual a 0 #
			$precio_mensual=number_format($precio_mensual,2,'.','');
			if($precio_mensual<0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El precio mensual del servicio no puede ser menor o igual a 0",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}


			$servicio_datos_reg=[
				[
					"campo_nombre"=>"servicios_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$nombre
				],
				[
					"campo_nombre"=>"servicios_precio_mensual",
					"campo_marcador"=>":Precio_Mensual",
					"campo_valor"=>$precio_mensual
				],
                [
					"campo_nombre"=>"servicios_velocidad_bajada",
					"campo_marcador"=>":Velocidad_Bajada",
					"campo_valor"=>$velocidad_bajada
				],
                [
					"campo_nombre"=>"servicios_velocidad_subida",
					"campo_marcador"=>":Velocidad_Subida",
					"campo_valor"=>$velocidad_subida
				],
				[
					"campo_nombre"=>"servicios_categoria_id",
					"campo_marcador"=>":Categoria",
					"campo_valor"=>$categoria_id
				]
			];

			$registrar_servicio=$this->guardarDatos("servicios",$servicio_datos_reg);

			if($registrar_servicio->rowCount()==1){
				$alerta=[
					"tipo"=>"limpiar",
					"titulo"=>"Servicio registrado",
					"texto"=>"El servicio ".$nombre." $".$precio_mensual." se registro con exito",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar el servicio, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}


		/*----------  Controlador listar servicios  ----------*/
		public function listarServicioControlador($pagina,$registros,$url,$busqueda){

			$pagina=$this->limpiarCadena($pagina);
			$registros=$this->limpiarCadena($registros);

			$url=$this->limpiarCadena($url);
			$url=APP_URL.$url."/";

			$busqueda=$this->limpiarCadena($busqueda);
			$tabla="";

			$pagina = (isset($pagina) && $pagina>0) ? (int) $pagina : 1;
			$inicio = ($pagina>0) ? (($pagina * $registros)-$registros) : 0;

			if(isset($busqueda) && $busqueda!=""){

				$consulta_datos="SELECT * FROM servicios WHERE servicios_nombre LIKE '%$busqueda%' OR servicios_precio_mensual LIKE '%$busqueda%' ORDER BY servicios_nombre ASC LIMIT $inicio,$registros";

				$consulta_total="SELECT COUNT(servicios_id) FROM servicios WHERE servicios_nombre LIKE '%$busqueda%' OR servicios_precio_mensual LIKE '%$busqueda%'";

			}else{

				$consulta_datos="SELECT * FROM servicios ORDER BY servicios_nombre ASC LIMIT $inicio,$registros";

				$consulta_total="SELECT COUNT(servicios_id) FROM servicios";

			}

			$datos = $this->ejecutarConsulta($consulta_datos);
			$datos = $datos->fetchAll();

			$total = $this->ejecutarConsulta($consulta_total);
			$total = (int) $total->fetchColumn();

			$numeroPaginas =ceil($total/$registros);

			$tabla.='
		        <div class="table-container">
		        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
		            <thead>
		                <tr>
		                    <th class="has-text-centered">Nombre</th>
		                    <th class="has-text-centered">Precio (Mensual)</th>
		                    <th class="has-text-centered">Velocidad de bajada (Mbps)</th>
		                    <th class="has-text-centered">Velocidad de subida (Mbps)</th>
		                    <th class="has-text-centered">Organización</th>
		                    <th class="has-text-centered">Actualizar</th>
		                    <th class="has-text-centered">Eliminar</th>
		                </tr>
		            </thead>
		            <tbody>
		    ';

		    if($total>=1 && $pagina<=$numeroPaginas){
				$contador=$inicio+1;
				$pag_inicio=$inicio+1;
				foreach($datos as $rows){
					$tabla.='
						<tr class="has-text-centered" >
							<td>'.$rows['servicios_nombre'].'</td>
							<td>'.$rows['servicios_precio_mensual'].'</td>
							<td>'.$rows['servicios_velocidad_bajada'].'</td>
							<td>'.$rows['servicios_velocidad_subida'].'</td>
							<td>'.$rows['servicios_categoria_id'].'</td>
			                <td>
			                    <a href="'.APP_URL.'servicioUpdate/'.$rows['servicios_id'].'/" class="button is-success is-rounded is-small">
			                    	<i class="fas fa-sync fa-fw"></i>
			                    </a>
			                </td>
			                <td>
			                	<form class="FormularioAjax" action="'.APP_URL.'app/ajax/servicioAjax.php" method="POST" autocomplete="off" >

			                		<input type="hidden" name="modulo_servicio" value="eliminar">
			                		<input type="hidden" name="servicios_id" value="'.$rows['servicios_id'].'">

			                    	<button type="submit" class="button is-danger is-rounded is-small">
			                    		<i class="far fa-trash-alt fa-fw"></i>
			                    	</button>
			                    </form>
			                </td>
						</tr>
					';
					$contador++;
				}
				$pag_final=$contador-1;
			}else{
				if($total>=1){
					$tabla.='
						<tr class="has-text-centered" >
			                <td colspan="5">
			                    <a href="'.$url.'1/" class="button is-link is-rounded is-small mt-4 mb-4">
			                        Haga clic acá para recargar el listado
			                    </a>
			                </td>
			            </tr>
					';
				}else{
					$tabla.='
						<tr class="has-text-centered" >
			                <td colspan="5">
			                    No hay registros en el sistema
			                </td>
			            </tr>
					';
				}
			}

			$tabla.='</tbody></table></div>';

			### Paginacion ###
			if($total>0 && $pagina<=$numeroPaginas){
				$tabla.='<p class="has-text-right">Mostrando servicios <strong>'.$pag_inicio.'</strong> al <strong>'.$pag_final.'</strong> de un <strong>total de '.$total.'</strong></p>';

				$tabla.=$this->paginadorTablas($pagina,$numeroPaginas,$url,7);
			}

			return $tabla;
		}


		/*----------  Controlador eliminar servicio  ----------*/
		public function eliminarServicioControlador(){

			$id=$this->limpiarCadena($_POST['servicios_id']);

			# Verificando servicio #
		    $datos=$this->ejecutarConsulta("SELECT * FROM servicios WHERE servicios_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el servicio en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    $eliminarServicio=$this->eliminarRegistro("servicios","servicios_id",$id);

		    if($eliminarServicio->rowCount()==1){
		        $alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Servicio eliminado",
					"texto"=>"El servicio ".$datos['servicios_nombre']." $".$datos['servicios_precio_mensual']." ha sido eliminado del sistema correctamente",
					"icono"=>"success"
				];
		    }else{
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido eliminar el servicio ".$datos['servicios_nombre']." $".$datos['servicios_precio_mensual']." del sistema, por favor intente nuevamente",
					"icono"=>"error"
				];
		    }

		    return json_encode($alerta);
		}


		/*----------  Controlador actualizar servicio  ----------*/
		public function actualizarServicioControlador(){

			$id=$this->limpiarCadena($_POST['servicios_id']);

			# Verificando servicio #
		    $datos=$this->ejecutarConsulta("SELECT * FROM servicios WHERE servicios_id='$id'");
		    if($datos->rowCount()<=0){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos encontrado el servicio en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }else{
		    	$datos=$datos->fetch();
		    }

		    # Almacenando datos#
		    $nombre=$this->limpiarCadena($_POST['servicios_nombre']);
		    $precio_mensual=$this->limpiarCadena($_POST['servicios_precio_mensual']);
		    $velocidad_bajada=$this->limpiarCadena($_POST['servicios_velocidad_bajada']);
		    $velocidad_subida=$this->limpiarCadena($_POST['servicios_velocidad_subida']);
            $categoria_id=$this->limpiarCadena($_POST['servicios_categoria_id']);

		    # Verificando campos obligatorios #
		    if($precio_mensual=="" || $nombre=="" || $categoria_id==""){
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
		    if($this->verificarDatos("[0-9]{1,5}",$velocidad_bajada)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"LA VELOCIDAD DE BAJADA no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

            if($this->verificarDatos("[0-9]{1,5}",$velocidad_subida)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"LA VELOCIDAD DE SUBIDA no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    if($this->verificarDatos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#+\-\/ ]{1,100}",$nombre)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El NOMBRE DEL SERVICIO no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    if($this->verificarDatos("[0-9.]{1,25}",$precio_mensual)){
		    	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El PRECIO DEL SERVICIO no coincide con el formato solicitado",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    # Comprobando nombre del servicio #
		    if($datos['servicios_nombre']!=$nombre){
			    $check_nombre=$this->ejecutarConsulta("SELECT servicios_nombre FROM servicios WHERE servicios_nombre='$nombre'");
			    if($check_nombre->rowCount()>0){
			    	$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"El nombre o código del servicio ingresado ya se encuentra registrado en el sistema",
						"icono"=>"error"
					];
					return json_encode($alerta);
			        exit();
			    }
		    }

		    # Comprobando que el efectivo sea mayor o igual a 0 #
			$precio_mensual=number_format($precio_mensual,2,'.','');
			if($precio_mensual<0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El precio mensual del servicio no puede ser menor o igual a 0",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
			}

			$servicio_datos_up=[
				[
					"campo_nombre"=>"servicios_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$nombre
				],
				[
					"campo_nombre"=>"servicios_precio_mensual",
					"campo_marcador"=>":Precio_Mensual",
					"campo_valor"=>$precio_mensual
				],
                [
					"campo_nombre"=>"servicios_velocidad_bajada",
					"campo_marcador"=>":Velocidad_Bajada",
					"campo_valor"=>$velocidad_bajada
				],
                [
					"campo_nombre"=>"servicios_velocidad_subida",
					"campo_marcador"=>":Velocidad_Subida",
					"campo_valor"=>$velocidad_subida
				],
				[
					"campo_nombre"=>"servicios_categoria_id",
					"campo_marcador"=>":Categoria",
					"campo_valor"=>$categoria_id
				]
			];

			$condicion=[
				"condicion_campo"=>"servicios_id",
				"condicion_marcador"=>":ID",
				"condicion_valor"=>$id
			];

			if($this->actualizarDatos("servicios",$servicio_datos_up,$condicion)){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Servicio actualizado",
					"texto"=>"Los datos del servicio ".$datos['servicios_nombre']." $".$datos['servicios_precio_mensual']." se actualizaron correctamente",
					"icono"=>"success"
				];
			}else{
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No hemos podido actualizar los datos del servicio ".$datos['servicios_nombre']." $".$datos['servicios_precio_mensual'].", por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}

	}