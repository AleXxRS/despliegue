<?php

//Retorla la url del proyecto
function base_url()
{
    return BASE_URL;
}
function media()
{
    return BASE_URL . "Assets";
}

function headerAdmin($data = "")
{
    $view_header = "Views/Templates/header_admin.php";
    require_once($view_header);
}

function footerAdmin($data = "")
{
    $view_footer = "Views/Templates/footer_admin.php";
    require_once($view_footer);
}
function navAdmin($data = "")
{
    $view_nav = "Views/Templates/nav_admin.php";
    require_once($view_nav);
}

//Muestra información formateada
function dep($data)
{
    $format  = print_r('<pre>');
    $format .= print_r($data);
    $format .= print_r('</pre>');
    return $format;
}

function getModal(string $nameModal, $data)
{
    $view_modal = "Views/Templates/Modals/{$nameModal}.php";
    require_once $view_modal;
}

//Envio de correos
function sendEmail($data, $template)
{
    $asunto = $data['asunto'];
    $emailDestino = $data['email'];
    $empresa = NOMBRE_REMITENTE;
    $remitente = EMAIL_REMITENTE;
    //ENVIO DE CORREO
    /* estos son como los encabezados */
    $de = "MIME-Version: 1.0\r\n"; /* esto se usa para que el mensaje no caiga en la bandeja de spam */
    $de .= "Content-type: text/html; charset=UTF-8\r\n"; /* define el tipo de contenido que se envia  */
    $de .= "From: {$empresa} <{$remitente}>\r\n"; /* aqui se coloca el remitente */
    ob_start();
    require_once("Views/Template/Email/" . $template . ".php");/* con esto requrimos a la carpeta views,carpeta template, carpeta Email, y selecciomos el archuivo template que es igual a sendEmail que e en los controllers lo lo definimos a email_cambioPassword y le concatenamos .php  */
    $mensaje = ob_get_clean();
    $send = mail($emailDestino, $asunto, $mensaje, $de);
    return $send;
}

//Elimina exceso de espacios entre palabras
function strClean($strCadena)
{
    $string = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $strCadena);
    $string = trim($string); //Elimina espacios en blanco al inicio y al final
    $string = stripslashes($string); // Elimina las \ invertidas
    $string = str_ireplace("<script>", "", $string);
    $string = str_ireplace("</script>", "", $string);
    $string = str_ireplace("<script src>", "", $string);
    $string = str_ireplace("<script type=>", "", $string);
    $string = str_ireplace("SELECT * FROM", "", $string);
    $string = str_ireplace("DELETE FROM", "", $string);
    $string = str_ireplace("INSERT INTO", "", $string);
    $string = str_ireplace("SELECT COUNT(*) FROM", "", $string);
    $string = str_ireplace("DROP TABLE", "", $string);
    $string = str_ireplace("OR '1'='1", "", $string);
    $string = str_ireplace('OR "1"="1"', "", $string);
    $string = str_ireplace('OR ´1´=´1´', "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("is NULL; --", "", $string);
    $string = str_ireplace("LIKE '", "", $string);
    $string = str_ireplace('LIKE "', "", $string);
    $string = str_ireplace("LIKE ´", "", $string);
    $string = str_ireplace("OR 'a'='a", "", $string);
    $string = str_ireplace('OR "a"="a', "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("OR ´a´=´a", "", $string);
    $string = str_ireplace("--", "", $string);
    $string = str_ireplace("^", "", $string);
    $string = str_ireplace("[", "", $string);
    $string = str_ireplace("]", "", $string);
    $string = str_ireplace("==", "", $string);
    return $string;
}
//Genera una contraseña de 10 caracteres
function passGenerator($length = 10)
{
    $pass = "";
    $longitudPass = $length;
    $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
    $longitudCadena = strlen($cadena);

    for ($i = 1; $i <= $longitudPass; $i++) {
        $pos = rand(0, $longitudCadena - 1);
        $pass .= substr($cadena, $pos, 1);
    }
    return $pass;
}
//Genera un token
function token()
/* esta funcion token se usa en el archivo Login.php  en la funcion "resetPass" */
/* esta funcion crea un areglo de 4 arrays de 10 items rarmdon se almacena en la variable token
    retornara la variable token  */
{
    $r1 = bin2hex(random_bytes(10));
    $r2 = bin2hex(random_bytes(10));
    $r3 = bin2hex(random_bytes(10));
    $r4 = bin2hex(random_bytes(10));
    $token = $r1 . '-' . $r2 . '-' . $r3 . '-' . $r4;
    return $token;
}
//Formato para valores monetarios
function formatMoney($cantidad)
{
    $cantidad = number_format($cantidad, 2, SPD, SPM);
    return $cantidad;
}
