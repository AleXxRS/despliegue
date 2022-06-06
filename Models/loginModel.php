<?php
/* esta estructura es para crear un modelo */
class loginModel extends Mysql
{
	private $intIdUsuario;
	private $strUsuario;
	private $strPassword;
	private $strToken;

	public function __construct()
	{
		parent::__construct();
	}

	public function loginUser(string $usuario, string $password)
	{
		$this->strUsuario = $usuario;
		$this->strPassword = $password;
		$sql = "SELECT id_persona,status FROM usuario WHERE 
					email_user = '$this->strUsuario' and   /* aca colocamos entre comilla simples por que es un string, strUsuario verificamos si strUsuario es igual a email_user */
					password = '$this->strPassword' and  /* verifucamos si password es igual al strPassword que estamos enviando */
					status != 0 "; /* y que el estatus sea diferente de 0 quiere decir que no deba estar elimindado */
		$request = $this->select($sql);
		return $request;
	}
	public function sessionLogin(int $iduser)
	{
		$this->intIdUsuario = $iduser;
		//BUSCAR ROLE 
		/* con selec seleccionamos de la tabla p el id persona  */
		$sql = "SELECT p.id_persona,
							p.identificacion,
							p.nombres,
							p.apellidos,
							p.telefono,
							p.email_user,
							p.nit,
							p.nombreFiscal,
							p.direccionFiscal,
							r.id_rol,r.nombre_rol,
							p.status 
					FROM usuario p /* aqui le damos el alias p a usuarios  */
					INNER JOIN rol r  /* inner join se usa para comparar datos de 2 tablas diferentes, le colocamos el alias r a la tabla rol  */
					ON p.rol_id = r.id_rol /* con ON comparamos que el rol_id de la tabla p(usuario) sea igual al id rol de la tabla r(rol) */
					WHERE p.id_persona = $this->intIdUsuario";/* con WHERE indicamos que de la tabla p(usuario)el id_persona sea igual al id que estamos recibiendo en intIdUsuario */
		$request = $this->select($sql); /* luego con request almacenamos los datos que tiene select para almacenar los datos */
		/* $_SESSION['userData'] = $request; */
		return $request;
	}

	public function getUserEmail(string $strEmail) /*string $strEmail-> se coloca esto porque va a recibir un email tipo string  */
	{
		$this->strUsuario = $strEmail;
		$sql = "SELECT id_persona,nombres,apellidos,status FROM usuario WHERE 
					/* con eso hacemos dos validaciones 1° que el email de la base de datos(email_user) sea igual al email que estamos enviando y que es status sea 1*/
					email_user = '$this->strUsuario' and  
					status = 1 ";
		$request = $this->select($sql);
		return $request;
	}

	public function setTokenUser(int $idpersona, string $token)
	{
		$this->intIdUsuario = $idpersona;
		$this->strToken = $token;
		$sql = "UPDATE usuario SET token = ? WHERE id_persona = $this->intIdUsuario ";
		$arrData = array($this->strToken);
		$request = $this->update($sql, $arrData);
		return $request;
	}
	public function getUsuario(string $email, string $token)
	{
		$this->strUsuario = $email;
		$this->strToken = $token;
		$sql = "SELECT id_persona FROM usuario WHERE 
					email_user = '$this->strUsuario' and 
					token = '$this->strToken' and 					
					status = 1 ";
		$request = $this->select($sql);
		return $request;
	}

	public function insertPassword(int $idPersona, string $password)
	{
		$this->intIdUsuario = $idPersona;
		$this->strPassword = $password;
		$sql = "UPDATE usuario SET password = ?, token = ? WHERE id_persona = $this->intIdUsuario ";
		$arrData = array($this->strPassword, "");
		$request = $this->update($sql, $arrData);
		return $request;
	}
}
