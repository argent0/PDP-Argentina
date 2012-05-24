<?php 
ini_set("display_errors", "0");
//error_reporting(E_ALL);
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR );

include("configuracion.php");
include("conec.php");


/* Llamo a la funcion para que se conecte a la base, me devuelve la conexion */
$link=Conectarse($db_host,$db_user,$db_password,$db_schema);


$url_votamostodos = $_REQUEST["url_votamostodos"]; 
	

$leyes=mysql_query("select * from ley where url_votamostodos='$url_votamostodos'", $link);
$ley=mysql_fetch_array($leyes);
mysql_free_result($leyes);

$otras_leyes=mysql_query("select * from ley where url_votamostodos<>'$url_votamostodos' order by prioridad", $link);
			
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$ley["titulo_lleca"]?></title>
<link type="text/css" rel="stylesheet" href="style/style.css?version=<?=filemtime('style/style.css')?>" />
<script type="text/javascript" src="js/jquery.js?version=<?=filemtime('js/jquery.js')?>"></script>
<?php include_once("analytics-tracking.php") ?>
</head>

<body>
	<h1><?=$ley["titulo_lleca"]?></h1>
	<h2><a target="newTab" href="<?=$ley["url_diputados"]?>">Texto completo</a></h2>
	<h3>Votos a favor: <?=$ley["cant_votos_si"]?></h3>
	<h3>Votos en contra: <?=$ley["cant_votos_no"]?></h3>
	<a href="ley.php?url_votamostodos=<?=$url_votamostodos?>&accion=votar_si">Votar a Favor</a><br/>
	<a href="ley.php?url_votamostodos=<?=$url_votamostodos?>&accion=votar_no">Votar en Contra</a><br/>
	<br/>

	Otras leyes importantes:<br/>
	<?		
	while($ley=mysql_fetch_array($otras_leyes)) {
	?>					
		<a href="ley.php?url_votamostodos=<?=$ley["url_votamostodos"]?>"><?=$ley["titulo_lleca"]?></a><br/>
	<?		
	}
	?>
	<br/>
	<a href="facebook.com/votamostodosargentina"><img src="images/seguinos-facebook.gif" alt="Seguinos en facebook"/></a><br/>
	<a href="mailto:info@votamostodos.com.ar" title="Contactanos">info@votamostodos.com.ar</a><br/>
	
	
</body>
</html>
<?
mysql_free_result($otras_leyes);
?>										


