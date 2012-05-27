<?php 
ini_set("display_errors", "1");
//error_reporting(E_ALL);
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR | E_WARNING | E_PARSE);

include("configuracion.php");
include("conec.php");


/* Llamo a la funcion para que se conecte a la base, me devuelve la conexion */
$link=Conectarse($db_host,$db_user,$db_password,$db_schema);


$url_votamostodos = $_REQUEST["url_votamostodos"]; 

require 'facebook-php-sdk/facebook.php';
$facebook = new Facebook(array(
		'appId'  => $facebook_app_id,
		'secret' => $facebook_app_secret,
));

$id_facebook = $facebook->getUser();


$leyes=mysql_query("select * from ley where url_votamostodos='$url_votamostodos'", $link);
$ley=mysql_fetch_array($leyes);
mysql_free_result($leyes);

$accion = $_REQUEST["accion"]; 
if ($accion=="votar_si" || $accion=="votar_no" ) {
	//$id_facebook = $_REQUEST["id_facebook"];
	$usuarios=mysql_query("select * from usuario where id_facebook=$id_facebook", $link);
	$usuario=mysql_fetch_array($usuarios);
	mysql_free_result($usuarios);
	if (!$usuario){
		mysql_query("insert into usuario (id_facebook) values ($id_facebook)", $link);		
		$usuarios=mysql_query("select * from usuario where id_facebook=$id_facebook", $link);
		$usuario=mysql_fetch_array($usuarios);
		mysql_free_result($usuarios);
	}		
	$id_usuario=$usuario["id"];
	
	if ($accion=="votar_si") {
		mysql_query("insert into voto (id_ley, id_usuario, voto, momento) values (" . $ley["id"] . "," . $id_usuario . ",true,now())", $link);
		mysql_query("update ley set cant_votos_si=cant_votos_si+1 where id=". $ley["id"], $link);	
	}
	if ($accion=="votar_no") {
		mysql_query("insert into voto (id_ley, id_usuario, voto, momento) values (" . $ley["id"] . "," . $id_usuario . ",false,now())", $link);
		mysql_query("update ley set cant_votos_no=cant_votos_no+1 where id=". $ley["id"], $link);	
	}
	
	$leyes=mysql_query("select * from ley where url_votamostodos='$url_votamostodos'", $link);
	$ley=mysql_fetch_array($leyes);
	mysql_free_result($leyes);	
}

$cant_votos = $ley["cant_votos_si"] + $ley["cant_votos_no"];
if ($cant_votos>0) {
	$porcentaje_si = ceil(100 * $ley["cant_votos_si"] / $cant_votos);
	$porcentaje_no = floor(100 * $ley["cant_votos_no"] / $cant_votos);
} else {
	$porcentaje_si = 0;
	$porcentaje_no = 0;
}

if ($id_facebook){
	$votos=mysql_query("select voto.voto from voto, usuario where id_ley=" . $ley["id"] . " and voto.id_usuario=usuario.id and usuario.id_facebook=$id_facebook", $link);
	$voto=mysql_fetch_array($votos);
	mysql_free_result($votos);
}

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
		<link rel="stylesheet" type="text/css" href="<?=$contexto?>css/style.css?version=<?=filemtime('css/style.css')?>" />
		<!-- <script type="text/javascript" src="<?=$contexto?>js/jquery-1.7.2.min.js?version=<?=filemtime('js/jquery-1.7.2.min.js')?>"></script>  -->		
		<title>Votamos Todos - <?=$ley["titulo_lleca"]?></title>
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
	       js.src = "//connect.facebook.net/en_US/all.js";
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
		<div id="wrap">
			<div class="sombra-top-wrap">
			</div>
			<div id="header">
				<h2>Votamos Todos democracia participativa online</h2>
				<div id="menu">
					<a href="#">
						<div class="boton-conocenos">
							conocenos
						</div>
					</a>
					<a href="#" onclick="FB.login();return false;" id="auth-loggedout">
						<div class="boton-login">
							login
						</div>
					</a>
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
					<h2>Expediente <?=$ley["expediente"]?> : <?=recortar($ley["titulo_real"],70)?></h2>
				</div>
				<div class="banner-ley">
					<div class="contenido-banner">
						<div class="banner-parte-superior">
							<h5>Leer el proyecto de ley</h5>
							<div class="redes-sociales-banner">
								<div class="fb-like" data-send="true" data-layout="button_count" data-width="150" data-show-faces="false" data-font="arial">
								</div>
								<div class="twitterBanner">
									<a href="https://twitter.com/share" class="twitter-share-button" data-via="votamostodos" data-lang="es">Twittear</a>
									<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
								</div>
							</div>
						</div>
						<h1><?=$ley["titulo_lleca"]?></h1>
						<div class="cantidad-votos">
							<?=$cant_votos?> votos
						</div>

						<form name="votarForm" action="<?=$contexto?>ley/<?=$url_votamostodos?>" method="POST">
							<input id="accion" type="hidden" name="accion"/>
						</form>
						<script>
							function votar(voto){
								FB.getLoginStatus(function(response) {
									  if (response.status === 'connected') {
											document.getElementById('accion').value = voto;
										  document.forms["votarForm"].submit();				    
									  } else {
										  FB.login();					  
									  }
									 });			
							}
						</script>
						
						<?
						if ($voto){
							if ($voto["voto"]){
						?>		
								<div class="banner-botones-voto-a-favor">
									<a href="#">
										<div class="boton-positivo" onclick="return false;">
											<p><?=$porcentaje_si?>%</p>
										</div>
									</a>
									<a href="#">
										<div class="boton-negativo" onclick="return false;">
											<p><?=$porcentaje_no?>%</p>
										</div>
									</a>
								</div>								
						<?
							} else {
						?>		
								<div class="banner-botones-voto-en-contra">
									<a href="#" onclick="return false;">
										<div class="boton-positivo">
											<p><?=$porcentaje_si?>%</p>
										</div>
									</a>
									<a href="#" onclick="return false;">
										<div class="boton-negativo">
											<p><?=$porcentaje_no?>%</p>
										</div>
									</a>
								</div>								
						<?
							}
						} else {
						?>					
							<div class="banner-botones">
								<a href="#" onclick="votar('votar_si');return false;">
									<div class="boton-positivo">
										<p><?=$porcentaje_si?>%</p>
									</div>
								</a>
								<a href="#" onclick="votar('votar_no');return false;">
									<div class="boton-negativo">
										<p><?=$porcentaje_no?>%</p>
									</div>
								</a>
							</div>								
						<?
						}
						?>										
					</div>
				</div>

				<div class="fb-comments" data-href="http://www.votamostodos.com.ar/ley/<?=$url_votamostodos?>" data-num-posts="8" data-width="600">
				</div>
				<div class="fondo-otras-leyes">
					<div class="otras-leyes">
						<h4>OTRAS LEYES ACTIVAS</h4>
						<?		
						$otras_leyes=mysql_query("select * from ley where url_votamostodos<>'$url_votamostodos' order by prioridad", $link);						
						while($otra_ley=mysql_fetch_array($otras_leyes)) {
						?>					
							<h3>
								<a href="<?=$contexto?>ley/<?=$otra_ley["url_votamostodos"]?>"><?=recortar($otra_ley["titulo_lleca"],30)?></a>
							</h3>
						<?		
						}
						mysql_free_result($otras_leyes);						
						?>						
					</div>
				</div>

				<!--    ESTE DIV ES HAY QUE HABILITARLO CUANDO SE QUIERAN PONERL LINKS        -->
				<!--
				<div class="divisor-titulo">
					<h4>Mas informacion...</h4>
				</div>
				<div class="informacion-util">
					<h3>Definicion de ley de muerte digna u ortotanasia</h3>
				</div>
				-->
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
						<a href="mailto:info@votamostodos.com.ar">CONOCENOS</a>
						<a href="mailto:info@votamostodos.com.ar">info@votamostodos.com.ar</a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>