<?php 
include("configuracion.php");
include("conec.php");

/* Llamo a la funcion para que se conecte a la base, me devuelve la conexion */
$link=Conectarse($db_host,$db_user,$db_password,$db_schema);

$leyes=mysql_query("select url_votamostodos from ley order by prioridad", $link);
$ley=mysql_fetch_array($leyes);
mysql_free_result($leyes);


?>
<script type="text/javascript">
<!--
window.location = "<?=$contexto . "ley/" . $ley["url_votamostodos"] ?>";
//-->
</script>
