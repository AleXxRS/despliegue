<?php 

	class Permisos extends Controllers{
		public function __construct()
		{
			parent::__construct();
		}

		public function getPermisosRol(int $idrol)
		{
			$rolid = intval($idrol);
			if($rolid > 0)
			{
				$arrModulos = $this->model->selectModulos();
				$arrPermisosRol = $this->model->selectPermisosRol($rolid);
				$arrPermisos = array('r' => 0, 'w' => 0, 'u' => 0, 'd' => 0);
				/* 
				r=leer
				w=escribir
				u=actualizar
				d=borrar
				 */
				$arrPermisoRol = array('idrol' => $rolid );

				if(empty($arrPermisosRol))    /* con "empty" verificamos si la variable  "arrPermisosRol" esta vacio*/
				{
					for ($i=0; $i < count($arrModulos) ; $i++) { 

						$arrModulos[$i]['permisos'] = $arrPermisos;  /* con esto lo que hacemos es añadirle los permisos al lado de cada modulo */
					}
				}else{
					for ($i=0; $i < count($arrModulos); $i++) {

						$arrPermisos = array('r' => $arrPermisosRol[$i]['r'], 
											 'w' => $arrPermisosRol[$i]['w'], 
											 'u' => $arrPermisosRol[$i]['u'], 
											 'd' => $arrPermisosRol[$i]['d'] 
											);
						if($arrModulos[$i]['id_modulo'] == $arrPermisosRol[$i]['modulo_id'])  /* error de la linea 41 solucionado " se tiene que colocar las avriables de id_modulo modulo_id  de  la tabla sql " */
						{
							$arrModulos[$i]['permisos'] = $arrPermisos;
						}
					}
				}
				$arrPermisoRol['modulos'] = $arrModulos;
				$html = getModal("modalPermisos",$arrPermisoRol);
				//dep($arrPermisoRol);

			}
			die();
		}
		


		public function setPermisos()
		{
			if($_POST) /* validamos is estamos recbiendo informacion atravez del metodo POST de se asi se ejecuta la fancion */
			{
				$intIdrol = intval($_POST['idrol']); /* con esto convertimos a un entero lo que venga atravez del metdo POST del idrol */
				$modulos = $_POST['modulos'];
	
				$this->model->deletePermisos($intIdrol);
				foreach ($modulos as $modulo) {/*  con foreach recorremos la arrar de modulos y con as asignamos los valores del array "$modulos"y lo asiganmos a la variable "$modulo" */
					$idModulo = $modulo['idmodulo']; /* con esto accedemos  a los idmodulo que contiene la id del modulo estos datos se encuentran en la variable $modulo  */
					$r = empty($modulo['r']) ? 0 : 1; /* con empty verificamos si viene la "r"   si no fue enviado entonces sera colocado 0 de lo contrario sera 1  */
					$w = empty($modulo['w']) ? 0 : 1;
					$u = empty($modulo['u']) ? 0 : 1;
					$d = empty($modulo['d']) ? 0 : 1;
					$requestPermiso = $this->model->insertPermisos($intIdrol, $idModulo, $r, $w, $u, $d);
				}
				if($requestPermiso > 0) /* hacemos la validacion si "$requestPermiso" es mayor que 0 quiere decir los datos si se almacenaron correctamente  */
				{
					$arrResponse = array('status' => true, 'msg' => 'Permisos asignados correctamente.');
				}else{
					$arrResponse = array("status" => false, "msg" => 'No es posible asignar los permisos.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE); /* convertimos los datos del "$arrResponse" a formato json */
			}
			die();
		}
		
	
		

	}
 ?>