<?php

	namespace app\controllers;
	use app\models\mainModel;

	class facturasController extends mainModel{

/*----------  Controlador registrar cliente  ----------*/
		public function registrarFacturaControlador(){

			# Almacenando datos#
		    $nombre=$this->limpiarCadena($_POST['facturas_nombre']);
		    $etiqueta=$this->limpiarCadena($_POST['facturas_etiquetas']);
		    $precio=$this->limpiarCadena($_POST['facturas_precio']);
		    

		    # Verificando campos obligatorios #
            if($nombre=="" || $precio==""){
            	$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No has llenado todos los campos que son obligatorios",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
            }

			# Comprobando codigo de cliente #
		    $check_etiqueta=$this->ejecutarConsulta("SELECT facturas_etiqueta FROM facturas WHERE facturas_etiqueta='$etiqueta'");
		    if($check_etiqueta->rowCount()>=1){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"La etiqueta que ha ingresado ya se encuentra registrado en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

		    # Comprobando nombre de cliente #
		    $check_nombre=$this->ejecutarConsulta("SELECT facturas_nombre FROM facturas WHERE facturas_nombre='$nombre' AND facturas_nombre='$nombre'");
		    if($check_nombre->rowCount()>=1){
		        $alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"Ya existe una factura registrada con el mismo nombre.",
					"icono"=>"error"
				];
				return json_encode($alerta);
		        exit();
		    }

    		$facturas_datos_reg=[
				[
					"campo_nombre"=>"facturas_nombre",
					"campo_marcador"=>":Nombre",
					"campo_valor"=>$nombre
				],
				[
					"campo_nombre"=>"facturas_etiqueta",
					"campo_marcador"=>":Etiqueta",
					"campo_valor"=>$etiqueta
				],
				[
					"campo_nombre"=>"facturas_precio",
					"campo_marcador"=>":Precio",
					"campo_valor"=>$precio
				],
			];

			$registrar_facturas=$this->guardarDatos("facturas",$facturas_datos_reg);

			if($registrar_facturas->rowCount()==1){
				$alerta=[
					"tipo"=>"recargar",
					"titulo"=>"Factura registrada",
					"texto"=>"La factura ".$etiqueta." - ".$nombre." se registro con exito",
					"icono"=>"success"
				];
			}else{

				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"No se pudo registrar la factura, por favor intente nuevamente",
					"icono"=>"error"
				];
			}

			return json_encode($alerta);
		}
	

	/*----------  Controlador listar cajas  ----------*/
public function listarFacturasControlador($pagina, $registros, $url, $busqueda){
    $pagina = $this->limpiarCadena($pagina);
    $registros = $this->limpiarCadena($registros);

    $url = $this->limpiarCadena($url);
    $url = APP_URL . $url . "/";

    $busqueda = $this->limpiarCadena($busqueda);
    $tabla = "";

    $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
    $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

    if (isset($busqueda) && $busqueda != "") {
        $consulta_datos = "SELECT * FROM facturas WHERE facturas_nombre LIKE '%$busqueda%' OR facturas_etiqueta LIKE '%$busqueda%' ORDER BY facturas_etiqueta ASC LIMIT $inicio,$registros";

        $consulta_total = "SELECT COUNT(facturas_id) FROM facturas WHERE facturas_nombre LIKE '%$busqueda%' OR facturas_etiqueta LIKE '%$busqueda%'";
    } else {
        $consulta_datos = "SELECT * FROM facturas ORDER BY facturas_etiqueta ASC LIMIT $inicio,$registros";

        $consulta_total = "SELECT COUNT(facturas_id) FROM facturas";
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
                    <th class="has-text-centered">Etiqueta</th>
                    <th class="has-text-centered">Nombre</th>
                    <th class="has-text-centered">Precio</th>
                    <th class="has-text-centered">Actualizar</th>
                    <th class="has-text-centered">Eliminar</th>
                </tr>
            </thead>
            <tbody>
    ';

    if ($total >= 1 && $pagina <= $numeroPaginas) {
        $contador = $inicio + 1;
        $pag_inicio = $inicio + 1;
        foreach ($datos as $rows) {
            $tabla .= '
                <tr class="has-text-centered">
                    <td>' . $rows['facturas_etiqueta'] . '</td>
                    <td>' . $rows['facturas_nombre'] . '</td>
                    <td>' . $rows['facturas_precio'] . '</td>
                    <td>
                        <a href="' . APP_URL . 'facturasUpdate/' . $rows['facturas_id'] . '/" class="button is-success is-rounded is-small">
                            <i class="fas fa-sync fa-fw"></i>
                        </a>
                    </td>
                    <td>
                        <form class="FormularioAjax" action="' . APP_URL . 'app/ajax/facturasAjax.php" method="POST" autocomplete="off">
                            <input type="hidden" name="modulo_facturas" value="eliminar">
                            <input type="hidden" name="facturas_id" value="' . $rows['facturas_id'] . '">
                            <button type="submit" class="button is-danger is-rounded is-small">
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
        if ($total >= 1) {
            $tabla .= '
                <tr class="has-text-centered">
                    <td colspan="5">
                        <a href="' . $url . '1/" class="button is-link is-rounded is-small mt-4 mb-4">
                            Haga clic acá para recargar el listado
                        </a>
                    </td>
                </tr>
            ';
        } else {
            $tabla .= '
                <tr class="has-text-centered">
                    <td colspan="5">
                        No hay registros en el sistema
                    </td>
                </tr>
            ';
        }
    }

    $tabla .= '</tbody></table></div>';

    ### Paginacion ###
    if ($total > 0 && $pagina <= $numeroPaginas) {
        $tabla .= '<p class="has-text-right">Mostrando facturas <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';

        $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
    }

    return $tabla;
	}

	/*----------  Controlador eliminar caja  ----------*/
	public function eliminarFacturaControlador(){

		$id=$this->limpiarCadena($_POST['facturas_id']);

		# Verificando caja #
		$datos=$this->ejecutarConsulta("SELECT * FROM facturas WHERE facturas_id='$id'");
		if($datos->rowCount()<=0){
			$alerta=[
				"tipo"=>"simple",
				"titulo"=>"Ocurrió un error inesperado",
				"texto"=>"No hemos encontrado la factura en el sistema",
				"icono"=>"error"
			];
			return json_encode($alerta);
			exit();
		}else{
			$datos=$datos->fetch();
		}

		// # Verificando ventas #
		// $check_ventas=$this->ejecutarConsulta("SELECT facturas_id FROM facturas WHERE facturas_id='$id' LIMIT 1");
		// if($check_ventas->rowCount()>0){
		// 	$alerta=[
		// 		"tipo"=>"simple",
		// 		"titulo"=>"Ocurrió un error inesperado",
		// 		"texto"=>"No podemos eliminar la factura del sistema ya que tiene pagos asociados",
		// 		"icono"=>"error"
		// 	];
		// 	return json_encode($alerta);
		// 	exit();
		// }

		$eliminarFactura=$this->eliminarRegistro("facturas","facturas_id",$id);

		if($eliminarFactura->rowCount()==1){
			$alerta=[
				"tipo"=>"recargar",
				"titulo"=>"Factura eliminada",
				"texto"=>"La factura ".$datos['facturas_etiqueta']." - ".$datos['facturas_nombre']." ha sido eliminada del sistema correctamente",
				"icono"=>"success"
			];
		}else{
			$alerta=[
				"tipo"=>"simple",
				"titulo"=>"Ocurrió un error inesperado",
				"texto"=>"No hemos podido eliminar la factura ".$datos['facturas_nombre']." - ".$datos['facturas_nombre']." del sistema, por favor intente nuevamente",
				"icono"=>"error"
			];
		}

		return json_encode($alerta);
	}

	/*----------  Controlador actualizar caja  ----------*/
	public function actualizarFacturaControlador(){

		$id=$this->limpiarCadena($_POST['facturas_id']);

		# Verificando caja #
		$datos=$this->ejecutarConsulta("SELECT * FROM facturas WHERE facturas_id='$id'");
		if($datos->rowCount()<=0){
			$alerta=[
				"tipo"=>"simple",
				"titulo"=>"Ocurrió un error inesperado",
				"texto"=>"No hemos encontrado la factura en el sistema",
				"icono"=>"error"
			];
			return json_encode($alerta);
			exit();
		}else{
			$datos=$datos->fetch();
		}

		# Almacenando datos#
		$nombre=$this->limpiarCadena($_POST['facturas_nombre']);
		$etiqueta=$this->limpiarCadena($_POST['facturas_etiqueta']);
		$precio=$this->limpiarCadena($_POST['facturas_precio']);

		# Verificando campos obligatorios #
		if($nombre=="" || $precio==""){
			$alerta=[
				"tipo"=>"simple",
				"titulo"=>"Ocurrió un error inesperado",
				"texto"=>"No has llenado todos los campos que son obligatorios",
				"icono"=>"error"
			];
			return json_encode($alerta);
			exit();
		}

		# Comprobando numero de caja #
		if($datos['facturas_etiqueta']!=$etiqueta){
			$check_etiqueta=$this->ejecutarConsulta("SELECT facturas_etiqueta FROM facturas WHERE facturas_etiqueta='$etiqueta'");
			if($check_etiqueta->rowCount()>0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"La etiqueta de la facutra ingresada ya se encuentra registrado en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
				exit();
			}
		}

		# Comprobando nombre de caja #
		if($datos['facturas_nombre']!=$nombre){
			$check_nombre=$this->ejecutarConsulta("SELECT facturas_nombre FROM facturas WHERE facturas_nombre='$nombre'");
			if($check_nombre->rowCount()>0){
				$alerta=[
					"tipo"=>"simple",
					"titulo"=>"Ocurrió un error inesperado",
					"texto"=>"El nombre de la factura ingresado ya se encuentra registrado en el sistema",
					"icono"=>"error"
				];
				return json_encode($alerta);
				exit();
			}
		}

		$factura_datos_up=[
			[
				"campo_nombre"=>"facturas_nombre",
				"campo_marcador"=>":Nombre",
				"campo_valor"=>$nombre
			],
			[
				"campo_nombre"=>"facturas_etiqueta",
				"campo_marcador"=>":Etiqueta",
				"campo_valor"=>$etiqueta
			],
			[
				"campo_nombre"=>"facturas_precio",
				"campo_marcador"=>":Precio",
				"campo_valor"=>$precio
			],
		];

		$condicion=[
			"condicion_campo"=>"facturas_id",
			"condicion_marcador"=>":ID",
			"condicion_valor"=>$id
		];

		if($this->actualizarDatos("facturas",$factura_datos_up,$condicion)){
			$alerta=[
				"tipo"=>"recargar",
				"titulo"=>"Factura actualizada",
				"texto"=>"Los datos de la factura ".$datos['facturas_etiqueta']." - ".$datos['facturas_nombre']." se actualizaron correctamente",
				"icono"=>"success"
			];
		}else{
			$alerta=[
				"tipo"=>"simple",
				"titulo"=>"Ocurrió un error inesperado",
				"texto"=>"No hemos podido actualizar los datos de la factura ".$datos['facturas_etiqueta']." - ".$datos['facturas_nombre'].", por favor intente nuevamente",
				"icono"=>"error"
			];
		}

		return json_encode($alerta);
	}
}
