<?php 
ini_set("display_errors", "1");
//error_reporting(E_ALL);
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR | E_WARNING | E_PARSE);

include("configuracion.php");
include("conec.php");


/* Llamo a la funcion para que se conecte a la base, me devuelve la conexion */
$link=Conectarse($db_host,$db_user,$db_password,$db_schema);


$url_votamostodos = $_REQUEST["url_votamostodos"]; 

//$id_usuario=1;

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

/*
if ($id_usuario){
	$votos=mysql_query("select * from voto where id_ley=" . $ley["id"] . " and id_usuario=" . $id_usuario, $link);
	$voto=mysql_fetch_array($votos);
	mysql_free_result($votos);	
}
*/
if ($id_facebook){
	$votos=mysql_query("select voto.voto from voto, usuario where id_ley=" . $ley["id"] . " and voto.id_usuario=usuario.id and usuario.id_facebook=$id_facebook", $link);
	$voto=mysql_fetch_array($votos);
	mysql_free_result($votos);
}



$otras_leyes=mysql_query("select * from ley where url_votamostodos<>'$url_votamostodos' order by prioridad", $link);


			
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$ley["titulo_lleca"]?></title>
<link type="text/css" rel="stylesheet" href="<?=$contexto?>style/style.css?version=<?=filemtime('style/style.css')?>" />
<!-- <script type="text/javascript" src="<?=$contexto?>js/jquery-1.7.2.min.js?version=<?=filemtime('js/jquery-1.7.2.min.js')?>"></script>  -->
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
        xfbml      : true  // parse XFBML
      });

      // listen for and handle auth.statusChange events
      FB.Event.subscribe('auth.statusChange', function(response) {
        if (response.authResponse) {
          // user has auth'd your app and is logged into Facebook
          FB.api('/me', function(me){
            if (me.name) {
              document.getElementById('auth-displayname').innerHTML = me.name;
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

      // respond to clicks on the login and logout links
      document.getElementById('auth-loginlink').addEventListener('click', function(){
        FB.login();
        return false;
      }, false);
      document.getElementById('auth-logoutlink').addEventListener('click', function(){
        FB.logout();
        return false;
      }, false); 
    } 
  </script>

  <div id="auth-status">
    <div id="auth-loggedout">
      <a href="#" id="auth-loginlink">Login</a>
    </div>
    <div id="auth-loggedin" style="display:none">
      Hola, <span id="auth-displayname"></span>  
    (<a href="#" id="auth-logoutlink">logout</a>)
  </div>

	<h1><?=$ley["titulo_lleca"]?></h1>
	<h2><a target="newTab" href="<?=$ley["url_diputados"]?>">Texto completo</a></h2>
	<h3>Votos a favor: <?=$ley["cant_votos_si"]?></h3>
	<h3>Votos en contra: <?=$ley["cant_votos_no"]?></h3>

	<?
	if ($voto){
		echo "Votaste esta ley";
		if ($voto["voto"]){
			echo " a favor";
		} else {
			echo " en contra";
		}
		echo "<br/>";
		
	} else {
		echo "No votaste esta ley";
		echo "<br/>";
	}
	?>										

	
	<form name="votarForm" action="<?=$contexto?>ley/<?=$url_votamostodos?>" method="POST">
		<input id="accion" type="hidden" name="accion"/>
		<!-- <input id="id_facebook" type="hidden" name="id_facebook"/>  -->
	</form>
	<script>
		function votar(voto){
			FB.getLoginStatus(function(response) {
				  if (response.status === 'connected') {
				    //var uid = response.authResponse.userID;
				    //var accessToken = response.authResponse.accessToken;
						document.getElementById('accion').value = voto;
						//document.getElementById('id_facebook').value = uid;
					  document.forms["votarForm"].submit();				    
				  } else {
					  FB.login();					  
				  }
				 });			
		}
	</script>
	<a href="#" onclick="votar('votar_si');return false;">Votar a Favor</a><br/>
	<a href="#" onclick="votar('votar_no');return false;">Votar en Contra</a><br/>
	<br/>

	Otras leyes importantes:<br/>
	<?		
	while($ley=mysql_fetch_array($otras_leyes)) {
	?>					
		<a href="<?=$contexto?>ley/<?=$ley["url_votamostodos"]?>"><?=$ley["titulo_lleca"]?></a><br/>
	<?		
	}
	?>
	<br/>
	<a href="facebook.com/votamostodosargentina"><img src="<?=$contexto?>images/seguinos-facebook.gif" alt="Seguinos en facebook"/></a><br/>
	<a href="mailto:info@votamostodos.com.ar" title="Contactanos">info@votamostodos.com.ar</a><br/>
	
	
</body>
</html>
<?
mysql_free_result($otras_leyes);
?>										


