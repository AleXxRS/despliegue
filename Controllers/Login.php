<?php

class Login extends Controllers
{
	public function __construct()
	{
		/* isset se usa para verificar si existe, si alguien inicio sesion nos mostrara el dashboard */
		session_start();
		if (isset($_SESSION['login'])) {
			header('Location: ' . base_url() . 'dashboard');
		}
		parent::__construct();
	}

	public function login()
	{
		$data['page_tag'] = "Login - Tienda Virtual";
		$data['page_title'] = " Tienda Virtual";
		$data['page_name'] = "login ";
		$data['page_functions_js'] = "function_login.js";

		$this->views->getView($this, "login", $data);
	}

	public function loginUser()
	{

		/* si alguien hace una peticion post  entonces este if sera igual a 1 y se cumplira la funcion */
		if ($_POST) {
			if (empty($_POST['txtEmail']) || empty($_POST['txtPassword'])) /* si txtEmail y txtPassword estan vacios nos muestra un msg de error de lo contrario se ejecuta el else */ {
				$arrResponse = array('status' => false, 'msg' => 'Error de datos');
			} else {
				$strUsuario  =  strtolower(strClean($_POST['txtEmail'])); /* strtolower se usa para convertir las letras en minuscula strClean se usa para limpiar y tener una cadena pura  */
				$strPassword = hash("SHA256", $_POST['txtPassword']); /* SHA256 se usa para encriptar por medio ed hash */
				$requestUser = $this->model->loginUser($strUsuario, $strPassword);

				if (empty($requestUser)) {
					$arrResponse = array('status' => false, 'msg' => 'El usuario o la contraseña es incorrecto.');
				} else {
					$arrData = $requestUser;/* almacenamos el requestUser que es viene de arriba  en la variable arrData  */

					if ($arrData['status'] == 1) /* si el en array de arriba requestUser que se almacena en arrData el estatus es 1, inicia la funcion */ {
						$_SESSION['idUser'] = $arrData['id_persona'];
						$_SESSION['login'] = true;

						$arrData = $this->model->sessionLogin($_SESSION['idUser']);/* este sessionLogin es para recolectar los datos del usuario   */
						$_SESSION['userData'] = $arrData;

						$arrResponse = array('status' => true, 'msg' => 'ok');
					} else  /* si el status es inactivo entonces mostrara un mensaje de usuario inactivo */ {
						$arrResponse = array('status' => false, 'msg' => 'Usuario inactivo.');
					}
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function resetPass()
	{
		if ($_POST) {
			error_reporting(0); /* esto se coloca para no mostrar el error que sale en tablas  */

			if (empty($_POST['txtEmailReset'])) {/* si txtEmailReset viene vacio entonces mostrara un mensaje de error  */
				$arrResponse = array('status' => false, 'msg' => 'Error de datos');
			} else {
				$token = token(); /* cremos una variable token que almacenara la funcion token esta funcion esta definida en los hellpers */
				$strEmail  =  strtolower(strClean($_POST['txtEmailReset']));/* con strtolower convertimos las letras en minusculas pero antes con strClean limpiamos cualquir scrip  */
				$arrData = $this->model->getUserEmail($strEmail);

				if (empty($arrData)) {
					$arrResponse = array('status' => false, 'msg' => 'Usuario no existente. tu estas bobo o que ?');
				} else {
					$idpersona = $arrData['id_persona'];
					$nombreUsuario = $arrData['nombres'] . ' ' . $arrData['apellidos'];

					$url_recovery = base_url() . '/login/confirmUser/' . $strEmail . '/' . $token;

					$requestUpdate = $this->model->setTokenUser($idpersona, $token);

					/* este escrip es para el envio del email */
					$dataUsuario = array(
						'nombreUsuario' => $nombreUsuario,
						'email' => $strEmail,
						'asunto' => 'Recuperar cuenta - ' . NOMBRE_REMITENTE,
						'url_recovery' => $url_recovery

					);

					if ($requestUpdate) {
						$sendEmail = sendEmail($dataUsuario, 'email_cambioPassword');
						if ($sendEmail) {

							$arrResponse = array(
								'status' => true,

								'msg' => 'Se ha enviado un email a tu cuenta de correo para cambiar tu contraseña.'
							);
						} else {

							$arrResponse = array(
								'status' => false,
								'msg' => 'no es posible realizar el proceso, intenta mas tarde.'
							);
						}
					} else {
						$arrResponse = array(
							'status' => false,
							'msg' => 'no es posible realizar el proceso, intenta mas tarde.'
						);
					}
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		}
		die();
	}

	public function confirmUser(string $params)
	{

		if (empty($params)) {
			header('Location: ' . base_url());
		} else {
			$arrParams = explode(',', $params);
			$strEmail = strClean($arrParams[0]);/* se coloca str clean para evitar que algun usuario inyecte codigo sql */
			$strToken = strClean($arrParams[1]);
			$arrResponse = $this->model->getUsuario($strEmail, $strToken);
			if (empty($arrResponse)) {
				header("Location: " . base_url());
			} else {
				$data['page_tag'] = "Cambiar contraseña";
				$data['page_name'] = "cambiar_contrasenia";
				$data['page_title'] = "Cambiar Contraseña";
				$data['email'] = $strEmail;
				$data['token'] = $strToken;
				$data['idpersona'] = $arrResponse['id_persona'];
				$data['page_functions_js'] = "function_login.js";
				$this->views->getView($this, "cambiar_password", $data);
			}
		}
		die();
	}


	public function setPassword()
	{

		/* creamos una validacion si la id del usuario o el email o el token o el password o la confirmacion del password esta vacio regresa un estatus falso y nos muestra un error */
		if (empty($_POST['idUsuario']) || empty($_POST['txtEmail']) || empty($_POST['txtToken']) || empty($_POST['txtPassword']) || empty($_POST['txtPasswordConfirm'])) {

			$arrResponse = array(
				'status' => false,
				'msg' => 'Error de datos'
			);
		} else {
			$intIdpersona = intval($_POST['idUsuario']); /* con esto convertimos a entero el valor  idUsuario que viene */
			$strPassword = $_POST['txtPassword'];
			$strPasswordConfirm = $_POST['txtPasswordConfirm'];
			$strEmail = strClean($_POST['txtEmail']);
			$strToken = strClean($_POST['txtToken']);

			/* esto se hace por tema de seguridad
			ya se hizo en el js pero igual se hace aqui
			con esto verificamos que el password y la confirmacion del password sean iguales  */
			if ($strPassword != $strPasswordConfirm) {
				$arrResponse = array(
					'status' => false,
					'msg' => 'Las contraseñas no son iguales.'
				);
			} else {
				$arrResponseUser = $this->model->getUsuario($strEmail, $strToken); /* esto nos devuelve la id del usuario y se almacena en "arrResponseUser" */
				if (empty($arrResponseUser)) {/* verificamos que arrResponseUser que es la id del usuario no este vacia   */
					$arrResponse = array(
						'status' => false,
						'msg' => 'Erro de datos.'
					);
				} else {
					$strPassword = hash("SHA256", $strPassword); /* con esto encriptamos la contraseña */
					$requestPass = $this->model->insertPassword($intIdpersona, $strPassword);

					if ($requestPass) {
						$arrResponse = array(
							'status' => true,
							'msg' => 'Contraseña actualizada con éxito.'
						);
					} else {
						$arrResponse = array(
							'status' => false,
							'msg' => 'No es posible realizar el proceso, intente más tarde.'
						);
					}
				}
			}
		}
		echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
		die();
	}
}
