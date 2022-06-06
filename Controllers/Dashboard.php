<?php

class Dashboard extends Controllers
{
	public function __construct()
	{
		parent::__construct();
		/* con este if lo que hacemos validar si la variable login que se encuentra en el controlador login es verdadero
			de lo contrario nos redirige a la pagina de login */
		session_start(); /* este sesion start es para iniciar sesion */
		/* con esta funcion, hacemos que la presona tenga que estar registrada para poder iniciar de lo contrario solo te redirigira al login */
		if (empty($_SESSION['login'])) {
			header('Location: ' . base_url() . 'login');
		}
	}

	public function dashboard()
	{
		$data['page_id'] = 2;
		$data['page_tag'] = "Dashboard - Tienda Virtual";
		$data['page_title'] = "Dashboard - Tienda Virtual";
		$data['page_name'] = "dashboard";
		$this->views->getView($this, "dashboard", $data);
	}
}
