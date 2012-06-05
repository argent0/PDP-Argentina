<?php 
include("header-php.php");

$url_votamostodos = $_REQUEST["url_votamostodos"];
$url_esta_ley = $site_url . $contexto . "ley/" . $url_votamostodos; 

$leyes=mysql_query("select * from ley where url_votamostodos='$url_votamostodos'", $link);
$ley=mysql_fetch_array($leyes);
mysql_free_result($leyes);

$accion = $_REQUEST["accion"]; 
if ($accion=="votar_si" || $accion=="votar_no" ) {
	$usuarios=mysql_query("select * from usuario where id_facebook=$id_facebook", $link);
	$usuario=mysql_fetch_array($usuarios);
	mysql_free_result($usuarios);
	if (!$usuario){
		$user_profile = $facebook->api('/me','GET');
		$nombre = $user_profile['name'];
		$email = $user_profile['email'];
		mysql_query("insert into usuario (id_facebook, nombre, email) values ($id_facebook, '$nombre', '$email')", $link);		
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

$header_title = "Votamos Todos - " . $ley["titulo_lleca"];
$header_og_title = "Ley de " . $ley["titulo_lleca"];
$header_og_url = $site_url . $contexto . "ley/" . $url_votamostodos;
$menu_button1_label = "conocenos";
$menu_button1_url = $site_url . $contexto . "conocenos.php";



include("header-html.php");

?>
			<div id="contenido">
				<div class="titulo-con-fondo">
					<h2>Expediente <?=$ley["expediente"]?> : <?=recortar($ley["titulo_real"],70)?></h2>
				</div>
				<div class="banner-ley">
					<div class="contenido-banner">
						<div class="banner-parte-superior">
							<h5><a target="newTab" href="<?=$ley["url_diputados"]?>">Leer el proyecto de ley</a></h5>
							<div class="redes-sociales-banner">
								<div class="fb-like" data-layout="button_count" data-width="150" data-show-faces="false" data-font="arial">
								</div>
								<div class="twitterBanner">
									<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$url_esta_ley?>" data-text="Ley de <?=$ley["titulo_lleca"]?>" data-via="votamostodos" data-lang="es" data-dnt="true"  data-count="none">Twittear</a>
									<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script> 
								</div>
							</div>
						</div>
						<h1><?=$ley["titulo_lleca"]?></h1>
						<div class="cantidad-votos">
							<?=$cant_votos?> votos
						</div>

						<form name="votarForm" action="<?=$url_esta_ley?>" method="POST">
							<input id="accion" type="hidden" name="accion"/>
						</form>
						<script>
							function votar(voto){
								FB.getLoginStatus(function(response) {
								  if (response.status === 'connected') {
										document.getElementById('accion').value = voto;
									  document.forms["votarForm"].submit();				    
								  } else {
									  alert("Hay que hacer click en 'login' antes de poder votar.");
									}
								});
							}
						</script>
						<?
						if ($voto){
							if ($voto["voto"]){
						?>		
								<div class="banner-botones-voto-a-favor">
									<a href="#" style="cursor: default" onclick="return false;">
										<div class="boton-positivo">
											<p><?=$porcentaje_si?>%</p>
										</div>
									</a>
									<a href="#" style="cursor: default" onclick="return false;">
										<div class="boton-negativo">
											<p><?=$porcentaje_no?>%</p>
										</div>
									</a>
								</div>								
						<?
							} else {
						?>		
								<div class="banner-botones-voto-en-contra">
									<a href="#" style="cursor: default" onclick="return false;">
										<div class="boton-positivo">
											<p><?=$porcentaje_si?>%</p>
										</div>
									</a>
									<a href="#" style="cursor: default" onclick="return false;">
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

				<div class="fb-comments" data-href="<?=$url_esta_ley?>" data-num-posts="8" data-width="600">
				</div>
				<div class="fondo-otras-leyes">
					<div class="otras-leyes">
						<h4>OTRAS LEYES ACTIVAS</h4>
						<?		
						$otras_leyes=mysql_query("select * from ley where url_votamostodos<>'$url_votamostodos' and activa=true order by prioridad", $link);						
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
<?
include("footer.php");
?>