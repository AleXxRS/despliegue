<?php

class Usuarios extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		session_start();
		if (empty($_SESSION['login'])) {
			header('Location: ' . base_url() . 'login');
		}
	}

	public function Usuarios()
	{
		$data['page_tag'] = "Usuarios";
		$data['page_title'] = "USUARIOS <small> tienda virtual </small>";
		$data['page_name'] = "Usuarios";
		$data['page_functions_js'] = "functions_usuarios.js";
		$this->views->getView($this, "usuarios", $data);
	}

	public function setUsuario()
	{
		if ($_POST) {

			/* con empty verificamos si no existe ningun valor, si los datos son incorrectos nos muestra un msg */
			if (empty($_POST['txtIdentificacion']) || empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']) || empty($_POST['txtEmail']) || empty($_POST['listRolid']) || empty($_POST['listStatus'])) {
				$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
			}
			/* si los datos son correx */ else {
				/* creamos las variables para almacenar los datos  */

				$idUsuario = intval($_POST['idUsuario']); /* convertimos en "entero" la variable que venga de "$_POST['idUsuario']"  */
				$strIdentificacion = strClean($_POST['txtIdentificacion']); /*  "strClean" lo que hace es limpiar algun scrip que pueda ser introducido  */
				$strNombre = ucwords(strClean($_POST['txtNombre'])); /* "ucwords" lo que hace es colocar las primeras letras de las palabras en mayusculas */
				$strApellido = ucwords(strClean($_POST['txtApellido']));
				$intTelefono = intval(strClean($_POST['txtTelefono']));
				$strEmail = strtolower(strClean($_POST['txtEmail'])); /*  "strtolower" lo que hace  es convertir todas las letras en minusculas */
				$intTipoId = intval(strClean($_POST['listRolid']));
				$intStatus = intval(strClean($_POST['listStatus']));

				if ($idUsuario == 0) {
					$option = 1;
					$strPassword =  empty($_POST['txtPassword']) ? hash("SHA256", passGenerator()) : hash("SHA256", $_POST['txtPassword']); /* con "hash" encriptamos la contraseña que venga de "passGenerator" ubicada eb los helpers*/
					$request_user = $this->model->insertUsuario(
						$strIdentificacion,
						$strNombre,
						$strApellido,
						$intTelefono,
						$strEmail,
						$strPassword,
						$intTipoId,
						$intStatus
					);
				} else {

					$option = 2;
					$strPassword =  empty($_POST['txtPassword']) ? "" : hash("SHA256", $_POST['txtPassword']);
					/*  "updateUsuario" esta variable viene de modelos de usuarios */
					$request_user = $this->model->updateUsuario(
						$idUsuario,
						$strIdentificacion,
						$strNombre,
						$strApellido,
						$intTelefono,
						$strEmail,
						$strPassword,
						$intTipoId,
						$intStatus
					);
				}

				if ($request_user > 0) {
					if ($option == 1) { /* si option es = a 1 quiere decir que el usuario existe y muestra el mensaje  */
						$arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente.');
					} else {/* si no es 1 entces es 2 , quiere decir que el usuario existe entonces los datos se actualizan */
						$arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente.');
					}
				} else if ($request_user == 'exist') {
					$arrResponse = array('status' => false, 'msg' => '¡Atención! el email o la identificación ya existe, ingrese otro.');
				} else {
					$arrResponse = array("status" => false, "msg" => 'No es posible almacenar los datos.');
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE); /* se coloca para convertir el arrar en  formato json */
		}
		die();
	}

	public function getUsuarios()
	{
		$arrData = $this->model->selectUsuarios();

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
			
				<button class="btn btn-info btn-sm btnViewUsuario" onClick="fntViewUsuario(' . $arrData[$i]['id_persona'] . ')" title="Ver usuario"><i class="far fa-eye"></i></button> 
				<button class="btn btn-primary  btn-sm btnEditUsuario" onClick="fntEditUsuario(' . $arrData[$i]['id_persona'] . ')" title="Editar usuario"><i class="fas fa-pencil-alt"></i></button>
				<button class="btn btn-danger btn-sm btnDelUsuario" onClick="fntDelUsuario(' . $arrData[$i]['id_persona'] . ')" title="Eliminar usuario"><i class="far fa-trash-alt"></i></button>
				</div>';
		}
		echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
		die();
	}

	public function getUsuario(int $diPersona)
	{
		$idUsuario = intval($diPersona); {
			if ($idUsuario > 0) /* si la variable "idUsuario" es mayor a 0 quiere decir que si es una id valida y si existe  */ {
				$arrData = $this->model->selectUsuario($idUsuario); /* entonces creamos un array la cual tendra los valores del model "selectUsuario"  */

				if (empty($arrData)) {
					$arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
				} else {
					$arrResponse = array('status' => true, 'data' => $arrData);
				}
				echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			}
			die();
		}
	}

	public function delUsuario()
	{
		if ($_POST) { /* si hay una peticion  "$_POST"  se ejecuta el scrip*/
			$intIdpersona = intval($_POST['idUsuario']);/*  con "intval" convertimos a entero el "idrol", este "idUsuario" es el que se usa en el js   */
			$requestDelete = $this->model->deleteUsuario($intIdpersona); /* con esto hacemos el llamado al modelo "deleteRol"  */
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
