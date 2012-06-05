<?php 

include("configuracion.php");

include("conec.php");

/* Llamo a la funcion para que se conecte a la base, me devuelve la conexion */
$link=Conectarse($db_host,$db_user,$db_password,$db_schema);

if ($modo == "prod") {
	ini_set("display_errors", "0");
	error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_WARNING | E_PARSE);
} else if ($modo == "qa") {
	ini_set("display_errors", "0");
	error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_WARNING | E_PARSE);
} else {
	ini_set("display_errors", "1");
	//error_reporting(E_ALL);
	error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR | E_WARNING | E_PARSE);
}


require 'facebook-php-sdk/facebook.php';
$facebook = new Facebook(array(
		'appId'  => $facebook_app_id,
		'secret' => $facebook_app_secret,
));

$id_facebook = $facebook->getUser();


function recortar($texto, $cant_caracteres){
	if (strlen($texto) <= $cant_caracteres){
		return $texto;
	} else {
		return substr($texto,0,$cant_caracteres-4) . "...";
	}
}

?>
