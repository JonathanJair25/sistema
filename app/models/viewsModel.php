<?php
	
	namespace app\models;

	class viewsModel{

		/*---------- Modelo obtener vista ----------*/
		protected function obtenerVistasModelo($vista){

			$listaBlanca=["pagoNew","pagoList","pagoSearch","sshNew","dashboard","entradaNew","entradaList","entradaSearch","entradaDetail","facturasSearch","facturasUpdate","facturasNew","facturasList","productPagos","cashierNew","cashierList","cashierSearch","cashierUpdate","userNew","userList","userUpdate","userSearch","userPhoto","clientNew","clientList","clientSearch","clientUpdate","categoryNew","categoryList","categorySearch","categoryUpdate","productNew","productList","productSearch","productUpdate","productPhoto","productCategory","companyNew","saleNew","saleList","saleSearch","saleDetail","servicioNew","servicioList","servicioSearch","servicioUpdate","logOut"];

			if(in_array($vista, $listaBlanca)){
				if(is_file("./app/views/content/".$vista."-view.php")){
					$contenido="./app/views/content/".$vista."-view.php";
				}else{
					$contenido="404";
				}
			}elseif($vista=="login" || $vista=="index"){
				$contenido="login";
			}else{
				$contenido="404";
			}
			return $contenido;
		}

	}