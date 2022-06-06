<?php 
	class RolesModel extends Mysql
	{
		public $intIdrol;
		public $strRol;
		public $strDescripcion;
		public $intStatus;

		public function __construct()
		{
			parent::__construct();
		}	

		 /* extrae Roles */
		 public function selectRoles()
		 {
			 $sql ="SELECT * FROM rol WHERE status != 0"; /* esto quiere decir es que va a seleccionar de la tabla rol todos los registros dierentes de 0 */
			 $request = $this->select_all($sql);/* con esto enviamos el el codigo de arriva como parametro a select_all que pertenece al archivo Mysql */
			 return $request;/* con esto retornamos a donde se requiera el metodo selectRoles */
		 }
		 
		 public function selectRol( int $idrol) /*  el nombre que le colocamos en el controlador debe ser el mismo en este caso "selectRol" */
		{
			//BUSCAR ROL
			$this->intIdrol = $idrol;
			$sql = "SELECT * FROM rol WHERE id_rol = $this->intIdrol";
			$request = $this->select($sql);
			return $request;
		}


		public function insertRol(string $rol, string $descripcion, int $status){

			$return = "";
			$this->strRol = $rol;
			$this->strDescripcion = $descripcion;
			$this->intStatus = $status;

			$sql = "SELECT * FROM rol WHERE nombre_rol = '{$this->strRol}' ";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO rol(nombre_rol,descripcion_rol,status) VALUES(?,?,?)";
	        	$arrData = array($this->strRol, $this->strDescripcion, $this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
			return $return;
		}



		/* modelos para actualizar roles  */
		public function updateRol(int $idrol, string $rol, string $descripcion, int $status)/* estos parametros vienen del controlador con el nombre //ACTUALIZAR */
		{
			$this->intIdrol = $idrol;
			$this->strRol = $rol;
			$this->strDescripcion = $descripcion;
			$this->intStatus = $status;

			$sql = "SELECT * FROM rol WHERE nombre_rol = '$this->strRol' AND id_rol != $this->intIdrol"; /* si al momento de editar al usuario la id es diferente a la que tenia se salta al else dando como resultado un mensaje de exist  */
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$sql = "UPDATE rol SET nombre_rol = ?, descripcion_rol = ?, status = ? WHERE id_rol = $this->intIdrol ";
				$arrData = array($this->strRol, $this->strDescripcion, $this->intStatus);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;			
		}

		public function deleteRol(int $idrol)
		{
			$this->intIdrol = $idrol;
			$sql = "SELECT * FROM usuario WHERE rol_id = $this->intIdrol";
			$request = $this->select_all($sql);
			if(empty($request)) /* un usuario no tiene la misma id quiere decir que esta vacio y se ejecuta la funcion */
			{
				$sql = "UPDATE rol SET status = ? WHERE id_rol = $this->intIdrol ";/*  aca se usa un "UPDATE" y no un DELETE , es recomendable usar el "UPDATE" para asi tener un historial */
				$arrData = array(0); /* ese arra y esta vacion */
				$request = $this->update($sql,$arrData); /* con update actualizamos los datos y se coloca en array vacio, eliminando los datos  */
				if($request) /* si los datos se eliminaron con exito se devolvera un ok  */

				{
					$request = 'ok';	
				}else{  /* de lo contrario si no se pudo eliminar se devolvera un error */
					$request = 'error';
				}
			}else{ /*  si un usuario tiene la misma id del rol devolvera un exist */
				$request = 'exist';
			}
			return $request;
		}
	}
 ?>