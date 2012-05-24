<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<script type="text/javascript" src="js/jquery.js"></script>
	</head>
	<body>
		Trayendo data de fb
	</body>	
</html>	
<?php 
ini_set("display_errors", "1");
error_reporting(E_ALL);
//error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR );

include("configuracion.php");



flush();


// El codigo que te da facebook para poder pedir un access_token
// La primera vez que se entra a esta pagina no viene el code.
$code = $_REQUEST["code"]; 


// La url a la que facebook tiene que redirigir una vez que el usuario permitió usar la aplicacion Dharma   
$redirect_url = $site_url . "/facebook.php";

if(empty($code)) {
    // La url para que el usuario acepte usar la aplicacion Dharma.
    // Facebook le va a pedir al usuario que 1) se loginee en facebook y 2) que acepte la aplicacion Dharma
    // Si el usuario ya está logineado y ya había aceptado usar Dharma anteriormente, facebook automaticamente
    // redirige a $redirect_url
    $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
        . $facebook_app_id . "&redirect_uri=" . urlencode($redirect_url) . "&scope=email,user_photos,friends_photos,friends_relationships,friends_relationship_details";
    echo "<script> top.location.href='" . $dialog_url . "'</script>";
} else {
		echo "<script> top.location.href='" . $site_url . "/facebook2.php?code=" . $code . "'</script>";
}
   
?>