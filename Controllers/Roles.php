<?php

class Roles extends Controllers
{
	public function __construct()
	{
		parent::__construct();
	}

	public function Roles()
	{
		$data['page_id'] = 3;
		$data['page_tag'] = "Roles Usuario";
		$data['page_name'] = "rol_usuario";
		$data['page_title'] = "Roles Usuario <small>Tienda Virtual</small>";
		$data['page_functions_js'] = "functions_roles.js";
		$this->views->getView($this, "roles", $data);
	}


	/* este es el campo de los botones de acciones */
	public function getRoles()
	{
		$arrData = $this->model->selectRoles(); /* "selectRoles" viene de rolesModel en la carpeta Models */

		for ($i = 0; $i < count($arrData); $i++) {
			if ($arrData[$i]['status'] == 1) {
				$arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
			} else {
				$arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
			}


			/* qui agregamos los botones de accion "ver, editar,eliminar " */
			/* el evento "onClick" se coloca para solucionar el no funcionamiento de los botones al tener mas de una pagina  */
			/* lo que hace es que al hacer click al boton este llama a la respectiva funcion en js ya sea ver, editar o borrar usuraios */

			$arrData[$i]['options'] = '<div class="text-center">
			<button class="btn btn-secondary btn-sm btnPermisosRol" onClick="fntPermisos(' . $arrData[$i]['id_rol'] . ')" title="Permisos"><i class="fas fa-key"></i></button>
			<button class="btn btn-primary btn-sm btnEditRol"  onClick="fntEditRol(' . $arrData[$i]['id_rol'] . ')" title="Editar"><i class="fas fa-pencil-alt"></i></button>
			<button class="btn btn-danger btn-sm btnDelRol" onClick="fntDelRol(' . $arrData[$i]['id_rol'] . ')" title="Eliminar"><i class="far fa-trash-alt"></i></button>
				
			</div>';
		}
		/* para que cualquier lenguaje de programacion pueda interpretar el array de arriva es necesario convertirlo a formato json IMPORTANTE */
		echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		die();
	}

	public function getSelectRoles()
	{
		/* creamos las variables  */
		$htmlOptions = "";
		$arrData = $this->model->selectRoles(); /* extraemos el array de los roles ubicada en el modelo y lo almacenamos en "arrData"*/
		if (count($arrData) > 0) { /* si en el conteo del array es mayor a 0 quiere decir qu exixten datos en el array y se ejecuta la funcion */
			for ($i = 0; $i < count($arrData); $i++) { /* recorremos el array con el ciclo for  */
				if ($arrData[$i]['status'] == 1) {  /*  si el rol esta inactivo entonces no se mostra en los options al momento de crear un usuario */
					$htmlOptions .= '<option value="' . $arrData[$i]['id_rol'] . '">' . $arrData[$i]['nombre_rol'] . '</option>'; /* con el punto en $"htmlOptions ." quiere decir que vamos a concatenar */ /* estos opcion son los que vamos a mostrar en el html */
				}
			}
		}
		echo $htmlOptions;
		die();
	}


	/* EXTRAER DATOS DE LA BASE DE DATOS */
	public function getRol(int $idrol) /* recibira un parametro entero $idrol */
	{
		/* if($_SESSION['permisosMod']['r']){ */
		$intIdrol = intval(strClean($idrol)); /* con intval nos aseguramos que el valor sea un entero  */
		if ($intIdrol > 0) {
			$arrData = $this->model->selectRol($intIdrol);
			if (empty($arrData)) {
				$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');/* si secoloca un id invalido como 100 y no se tiene 100 registros muesra este mensaje   */
			} else {
				$arrResponse = array('status' => true, 'data' => $arrData);
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE); /* con esto convertimos al formato JSON el array "$arrResponse "  */
		}
		/* } */
		die();
	}


	/* INSERTAR NUEVO ROL */
	public function setRol()
	{
		$intIdrol = intval($_POST['idRol']); /* ese idRol es el id de cada rol  */
		$strRol =  strClean($_POST['txtNombre']);
		$strDescipcion = strClean($_POST['txtDescripcion']);
		$intStatus = intval($_POST['listStatus']); /* se coloca intval ara poder obtener un entero puede ser 1 o 2  */


		if ($intIdrol == 0) /* si el id del rol es 0 quire decir que se esta crando un nuevo rol y "$option"  toma valor a 1*/ {
			//Crear
			$request_rol = $this->model->insertRol($strRol, $strDescipcion, $intStatus);
			$option = 1;
			/* if($_SESSION['permisosMod']['w']){
						$request_rol = $this->model->insertRol($strRol, $strDescipcion,$intStatus);
						$option = 1; */
		} else {   /* si el id es diferente de 0 quiere decir que el rol ya existe  y $option toma valor de 2  este se usara mas abajo*/
			//Actualizar
			$request_rol = $this->model->updateRol($intIdrol, $strRol, $strDescipcion, $intStatus);
			$option = 2;


			/* if($_SESSION['permisosMod']['u']){
						$request_rol = $this->model->updateRol($intIdrol, $strRol, $strDescipcion, $intStatus);
						$option = 2; */
		}

		if ($request_rol > 0) {
			if ($option == 1) /* si $option es igual a 1 como se declaro arriva  dara el siguiente mesaje  */ {
				$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
			} else {   	/* si no es 1 entoces es 2 y se mostrar el siguiente mensaje */
				$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
			}
		} else if ($request_rol == 'exist') {
			$arrResponse = array('status' => false, 'msg' => '¡Atención! El Nombre ya existe.');
		} else {
			$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		die();
	}

	public function delRol()
	{
		if ($_POST) { /* si hay una peticion  "$_POST"  se ejecuta el scrip*/
			$intIdrol = intval($_POST['idrol']);/*  con "intval" convertimos a entero el "idrol" */
			$requestDelete = $this->model->deleteRol($intIdrol); /* con esto hacemos el llamado al modelo "deleteRol"  */
			if ($requestDelete == 'ok') {
				$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Rol');
			} else if ($requestDelete == 'exist') {
				$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Rol asociado a usuarios.');
			} else {
				$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Rol.');
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE); /* con "json_encode" convertimos a formato json "arrResponse" */


			/* if($_SESSION['permisosMod']['d']){
				} */
		}
		die(); /* si no hay una peticion "$_POST" se cerrara con die */
	}
}
