<?php 
include("configuracion.php");

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

include("conec.php");

/* Llamo a la funcion para que se conecte a la base, me devuelve la conexion */
$link=Conectarse($db_host,$db_user,$db_password,$db_schema);

require 'jsonwrapper/jsonwrapper.php';
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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
		<meta HTTP-EQUIV="EXPIRES" CONTENT="-1">
		<link rel="stylesheet" type="text/css" href="<?=$contexto?>css/style.css?version=<?=filemtime('css/style.css')?>" />
		<!-- <script type="text/javascript" src="<?=$contexto?>js/jquery-1.7.2.min.js?version=<?=filemtime('js/jquery-1.7.2.min.js')?>"></script>  -->		
		<title>Votamos Todos - Conocenos</title>
		<meta property="og:title" content="Votamos Todos - Conocenos" />
		<meta property="og:type" content="cause" />
		<meta property="og:url" content="<?=$site_url . $contexto?>conocenos.php" />
		<meta property="og:image" content="<?=$site_url . $contexto?>img/logo-cuadrado.jpg" />
		<meta property="og:site_name" content="Votamos Todos" />
		<meta property="fb:app_id" content="<?=$facebook_app_id?>" />
		<?php include_once("analytics-tracking.php") ?>
	</head>
	<body>
		<div id="fb-root"></div>
		<script>
	    // Load the SDK Asynchronously
	    (function(d){
	       var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
	       if (d.getElementById(id)) {return;}
	       js = d.createElement('script'); js.id = id; js.async = true;
	       js.src = "//connect.facebook.net/es_ES/all.js";
	       ref.parentNode.insertBefore(js, ref);
	     }(document));
	
	    // Init the SDK upon load
	    window.fbAsyncInit = function() {
	      FB.init({
	        appId      : '<?=$facebook_app_id?>', // App ID
	        channelUrl : '//'+window.location.hostname+'/channel.php', // Path to your Channel File
	        status     : true, // check login status
	        cookie     : true, // enable cookies to allow the server to access the session
	        xfbml      : true,  // parse XFBML
	        oauth : true
	      });
	
	      // listen for and handle auth.statusChange events
	      FB.Event.subscribe('auth.statusChange', function(response) {
	        if (response.authResponse) {
	          // user has auth'd your app and is logged into Facebook
	          FB.api('/me', function(me){
	            if (me.name) {
	              document.getElementById('auth-displayname').innerHTML = "Bienvenido, " + me.name;
	              document.getElementById('auth-displayimage').src = "https://graph.facebook.com/" + me.id + "/picture?type=square";
	            }
	          });
	          document.getElementById('auth-loggedout').style.display = 'none';
	          document.getElementById('auth-loggedin').style.display = 'block';
	        } else {
	          // user has not auth'd your app, or is not logged into Facebook
	          document.getElementById('auth-loggedout').style.display = 'block';
	          document.getElementById('auth-loggedin').style.display = 'none';
	        }
	      });
	
	      FB.Event.subscribe('auth.login', function(response) {
	        window.location.reload();
	      });
	      FB.Event.subscribe('auth.logout', function(response) {
	        window.location.reload();
	      });
	    } 
	  </script>
	
	  </script>
		<div id="wrap">
			<div class="sombra-top-wrap">
			</div>
			<div id="header">
				<h2>Votamos Todos democracia participativa online</h2>
				<div id="menu">
					<a href="<?=$site_url . $contexto?>">
						<div class="boton-conocenos">
							votar ahora
						</div>
					</a>
					<a href="#" onclick="FB.login();return false;" id="auth-loggedout">
						<div class="boton-login">
							login
						</div>
					</a>
					<?
					/*
					<a href="#" onclick="return false;" id="auth-loggedout">
						<div class="boton-login">
							<div class="fb-login-button" data-show-faces="false" size="large" data-width="250" data-max-rows="1">
              </div>
						</div>
					</a>
					*/
					?>
					
				</div>
				<!--                **********  ESTE DIV SE OCULTA CUANDO NO HICISTE LOGIN  **********   -->
				<div class="loguineado" id="auth-loggedin" style="display:none">
					<img id="auth-displayimage" width="50" height="50" />
					<div class="nombreLogin" id="auth-displayname">						
					</div>
					<a href="#" onclick="FB.logout();return false;">
						LOGOUT
					</a>
				</div>
				<!--                **********  ******************************************  **********   -->
				<img src="img/divisor.jpg" width="984" height="3" />
			</div>
      
      <div id="contenido">
	     	<div class="titulo-con-fondo">
       		<h2>CONOCENOS</h2>
				</div>
        <div class="tituloCheckbox">
        
    	   	<h2>&iquest;Cual es el objetivo de Votamos Todos?</h2>
          <h3>Mejorar la calidad de la democracia consiguiendo que sea mas representativa.</h3>
        </div>  
        <div class="tituloCheckbox">
        	<h2>&iquest;Ccomo se financia Votamos Todos?</h2>
          <h3>Mejorar la calidad de la democracia consiguiendo que sea mas representativa.</h3>
        </div>  
        <div class="tituloCheckbox">
        	<h2>&iquest;Cual es el objetivo de Votamos Todos?</h2>
          <h3>Mejorar la calidad de la democracia consiguiendo que sea mas representativa. Mejorar la calidad de la democracia consiguiendo que sea mas representativa. Mejorar la calidad de la democracia consiguiendo que sea mas representativa. Mejorar la calidad de la democracia consiguiendo que sea mas representativa.</h3>
        </div>                          
			</div>    
       
			<div id="footer">
				<div class="footer-todas-leyes">
					<?		
					$todas_leyes=mysql_query("select * from ley order by prioridad", $link);						
					while($una_ley=mysql_fetch_array($todas_leyes)) {
					?>					
						<h3>
							<a href="<?=$contexto?>ley/<?=$una_ley["url_votamostodos"]?>"><?=recortar($una_ley["titulo_lleca"],35)?></a>
						</h3>
					<?		
					}
					mysql_free_result($todas_leyes);						
					?>					
				</div>
				<div class="base-footer">
					<div class="redes-sociales-footer">
						<a href="http://www.twitter.com/votamostodos"><img src="<?=$contexto?>img/icon-twitter.jpg" width="30" height="30" /></a>
						<a href="http://www.facebook.com/votamostodosargentina"><img src="<?=$contexto?>img/icon-facebook.jpg" width="30" height="30" /></a>
					</div>
					<div class="mail-footer">
						<a href="<?=$site_url . $contexto?>conocenos.php">CONOCENOS</a>
						<a href="mailto:info@votamostodos.com.ar">info@votamostodos.com.ar</a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>