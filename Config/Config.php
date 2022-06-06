<?php

define("BASE_URL", "http://localhost/_tienda/");
/* const BASE_URL = "https://abelosh.com/tienda_virtual/"; */
/* const BASE_URL = "https://alex.com/store"; */

//Zona horaria
date_default_timezone_set('America/Lima');

//Datos de conexión a Base de Datos
const DB_HOST = "localhost";
const DB_NAME = "db_tiendavirtual";
const DB_USER = "root";
const DB_PASSWORD = "";
const DB_CHARSET = "charset=utf8";

//Deliminadores decimal y millar Ej. 24,1989.00
const SPD = ".";
const SPM = ",";

//Simbolo de moneda
const SMONEY = "S/.";

//Datos envio de correo
const NOMBRE_REMITENTE = "Tienda Virtual";
const EMAIL_REMITENTE = "no-reply@abelosh.com"; /* con esto indicamos al remitente que no debe responder al correo que se esata enviando */
const NOMBRE_EMPRESA = "Tienda Virtual"; /* estas variables se usa en php  */
const WEB_EMPRESA = "ealexstorers.webcindario.com/";
/* const WEB_EMPRESA = "http://localhost/_tienda/login"; */
