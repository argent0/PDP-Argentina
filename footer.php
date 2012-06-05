<?php ?>
			<div id="footer">
				<div class="footer-todas-leyes">
					<?		
					$todas_leyes=mysql_query("select * from ley where activa=true order by prioridad", $link);						
					while($una_ley=mysql_fetch_array($todas_leyes)) {
					?>					
						<h3>
							<a href="<?=$contexto?>ley/<?=$una_ley["url_votamostodos"]?>"><?=recortar($una_ley["titulo_lleca"],33)?></a>
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