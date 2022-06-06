<?php
/* esta estructura es para crear un modelo */

/* con los modelos se crea las interacciones con la base de datos */
class UsuariosModel extends Mysql
{
	/* creamos las propiedades de tipo privada  */
	private $intIdUsuario;
	private $strIdentificacion;
	private $strNombre;
	private $strApellido;
	private $intTelefono;
	private $strEmail;
	private $strPassword;
	private $strToken;
	private $intTipoId;
	private $intStatus;

	public function __construct()
	{
		parent::__construct();
	}

	public function insertUsuario(string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, int $status)
	{

		/* colocamos los datos que recibimos a las variables de la parte superior  */
		$this->strIdentificacion = $identificacion;
		$this->strNombre = $nombre;
		$this->strApellido = $apellido;
		$this->intTelefono = $telefono;
		$this->strEmail = $email;
		$this->strPassword = $password;
		$this->intTipoId = $tipoid;
		$this->intStatus = $status;
		$return = 0;

		/* con esta linea hacemos validamos que si la identificacion o el email corresponden a las almacenadas entonces selecciona todos los campos y se almacena en "request" */
		$sql = "SELECT * FROM usuario WHERE 
						email_user = '{$this->strEmail}' or identificacion = '{$this->strIdentificacion}' ";
		$request = $this->select_all($sql);

		/* si "request" esta vacia entonces se insertan los valores de lo contrario retorna un exist */
		if (empty($request)) {
			$query_insert  = "INSERT INTO usuario(identificacion,nombres,apellidos,telefono,email_user,password,rol_id,status) 
									  VALUES(?,?,?,?,?,?,?,?)";
			$arrData = array(
				$this->strIdentificacion,
				$this->strNombre,
				$this->strApellido,
				$this->intTelefono,
				$this->strEmail,
				$this->strPassword,
				$this->intTipoId,
				$this->intStatus
			);
			$request_insert = $this->insert($query_insert, $arrData);
			$return = $request_insert;
		} else {
			$return = "exist";
		}
		return $return;
	}

	public function selectUsuarios()
	{
		$sql = "SELECT p.id_persona,p.identificacion,p.nombres,p.apellidos,p.telefono,p.email_user,p.status,r.nombre_rol  /*  con la p. indicamos que pertenecen a la tabla persona=usuario  */
					FROM usuario p  /* declaramos a la tabla usuario como p */
					INNER JOIN rol r /* declaramos a la tabla rol como r  */
					ON p.rol_id = r.id_rol /* igualamos que el rol_id de la tabla usuario sea igual al id_rol de la tabla rol */
					WHERE p.status != 0 "; /* si el estatus es diferente de 0, seleccionamos todos los datos que vengan en sql que son las tablas de usuario */
		$request = $this->select_all($sql); /* seleccionamos todos los elementos de la tabla usuario y lo almacenamos en $request*/
		return $request; /* retornamos los los $request */
	}

	public function selectUsuario(int $idPersona)
	{
		$this->intIdUsuario = $idPersona;

		$sql = "SELECT p.id_persona,p.identificacion,p.nombres,p.apellidos,p.telefono,p.email_user,p.nit,p.nombreFiscal,p.direccionFiscal,r.id_rol,r.nombre_rol,p.status, 
					DATE_FORMAT(p.date_created, '%d-%m-%Y') as fechaRegistro 

					FROM usuario p
					INNER JOIN rol r /* inner join se usa para extraer valores o datos de otras tablas */
					ON p.rol_id = r.id_rol
					WHERE p.id_persona = $this->intIdUsuario";
		$request = $this->select($sql);
		return $request;
	}

	public function updateUsuario(int $idUsuario, string $identificacion, string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid, int $status)
	{

		$this->intIdUsuario = $idUsuario;
		$this->strIdentificacion = $identificacion;
		$this->strNombre = $nombre;
		$this->strApellido = $apellido;
		$this->intTelefono = $telefono;
		$this->strEmail = $email;
		$this->strPassword = $password;
		$this->intTipoId = $tipoid;
		$this->intStatus = $status;


		/* hecemos una validacion 
			-con esto validamos si el mismo correo se esta usando para otro usuario
			-si el email_user es = al emal que estamos enviando y la id_persona es diferente a id que estamos enviando
			esta validacion es para identificar validar que el usuario */
		$sql = "SELECT * FROM usuario WHERE (email_user = '{$this->strEmail}' AND id_persona != $this->intIdUsuario)
										  OR (identificacion = '{$this->strIdentificacion}' AND id_persona != $this->intIdUsuario) ";
		$request = $this->select_all($sql);

		if (empty($request)) {
			if ($this->strPassword  != "") {
				$sql = "UPDATE usuario SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, password=?, rol_id=?, status=? 
							WHERE id_persona = $this->intIdUsuario ";
				$arrData = array(
					$this->strIdentificacion,
					$this->strNombre,
					$this->strApellido,
					$this->intTelefono,
					$this->strEmail,
					$this->strPassword,
					$this->intTipoId,
					$this->intStatus
				);
			} else {
				$sql = "UPDATE usuario SET identificacion=?, nombres=?, apellidos=?, telefono=?, email_user=?, rol_id=?, status=? 
							WHERE id_persona = $this->intIdUsuario ";
				$arrData = array(
					$this->strIdentificacion,
					$this->strNombre,
					$this->strApellido,
					$this->intTelefono,
					$this->strEmail,
					$this->intTipoId,
					$this->intStatus
				);
			}
			$request = $this->update($sql, $arrData);
		} else {
			$request = "exist";
		}
		return $request;
	}

	/* model para eliminar usuarios */
	public function deleteUsuario(int $intIdpersona)
	{
		$this->intIdUsuario = $intIdpersona;
		$sql = "UPDATE usuario SET status = ? WHERE id_persona = $this->intIdUsuario ";/*  aca se usa un "UPDATE" y no un DELETE , es recomendable usar el "UPDATE" para asi tener un historial */
		$arrData = array(0); /* ese array esta vacion */
		$request = $this->update($sql, $arrData); /* con update actualizamos los datos y se coloca en array vacio, eliminando los datos pero se queda guardado en la base de datos con un status 0 */

		return $request;
	}
}
