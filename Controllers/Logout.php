<!-- creamos el controlador para cerrar sesion  -->
<?php

class Logout
{
	public function __construct()
	{
		session_start(); /* iniciamos ssesion  */
		session_unset();/* limpiamos sesion */
		session_destroy(); /* destruimos sesion */
		header('location: ' . base_url() . 'login'); /* redireccionamos sesion cuando le den al boton cerramos sesion */
	}
}
