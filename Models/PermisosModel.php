<?php 
/* esta estructura es para crear un modelo */
	class PermisosModel extends Mysql
	{
		public $intIdpermiso;
		public $intRolid;
		public $intModuloid;
		public $r;
		public $w;
		public $u;
		public $d;

		public function __construct()
		{
			parent::__construct();
		}	

		public function selectModulos()
		{
			$sql = "SELECT * FROM modulo WHERE status != 0";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectPermisosRol(int $idrol)
		{
			$this->intRolid = $idrol;
			$sql = "SELECT * FROM permisos WHERE rol_id = $this->intRolid";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deletePermisos(int $idrol)
		{
			$this->intRolid = $idrol;
			$sql = "DELETE FROM permisos WHERE rol_id = $this->intRolid"; /* con la instruccion sql indicamos que elimanaremos de la tabla permisos si el rol_id sea igual al rol que estamos enviando */
			$request = $this->delete($sql); /* con request ejecutamos la funcion sql atravez del metodo delete */
			return $request;/* y finalmente retormas  "$request" este se ejecutara cada vez que se usa la funcion "deletePermisos"  */
		}

		public function insertPermisos(int $idrol, int $idmodulo, int $r, int $w, int $u, int $d){ /* recibimos los datos que enviamos de permisos.php */

			/* hacemos la equivalencia de los datos que recibimos de permisos.php de la funcion deletePermisos */
			$this->intRolid = $idrol; 
			$this->intModuloid = $idmodulo;
			$this->r = $r; 
			$this->w = $w;
			$this->u = $u;
			$this->d = $d;
			$query_insert  = "INSERT INTO permisos(rol_id,modulo_id,r,w,u,d) VALUES(?,?,?,?,?,?)";  /* insertamos los datos que recibimos  / VALUES debe tener la misma cantidad de campos que tiene la tabla en mysql*/
        	$arrData = array($this->intRolid, $this->intModuloid, $this->r, $this->w, $this->u, $this->d);
        	$request_insert = $this->insert($query_insert,$arrData);		
	        return $request_insert;
		}


	}
 ?>