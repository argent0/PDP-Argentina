<?php ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
		<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
		<meta HTTP-EQUIV="EXPIRES" CONTENT="-1">
		<link rel="stylesheet" type="text/css" href="<?=$contexto?>css/style.css?version=<?=filemtime('css/style.css')?>" />
		<script type="text/javascript" src="<?=$contexto?>js/jquery-1.7.2.min.js?version=<?=filemtime('js/jquery-1.7.2.min.js')?>"></script>		
		<title><?=$header_title?></title>
		<meta property="og:title" content="<?=$header_og_title?>" />
		<meta property="og:type" content="cause" />
		<meta property="og:url" content="<?=$current_url?>" />
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
	              document.getElementById('auth-displayname').innerHTML = "Usuario: " + me.name;
	              document.getElementById('auth-displayimage').src = "https://graph.facebook.com/" + me.id + "/picture?type=square";
	            }
	          });
	          document.getElementById('auth-loggedout').style.display = 'none';
	          document.getElementById('ayuda-login').style.display = 'none';
	          document.getElementById('auth-loggedin').style.display = 'block';
	        } else {
	          // user has not auth'd your app, or is not logged into Facebook
	          document.getElementById('auth-loggedout').style.display = 'block';
	          document.getElementById('ayuda-login').style.display = 'block';
	          document.getElementById('auth-loggedin').style.display = 'none';
	        }
	      });
	
	      FB.Event.subscribe('auth.login', function(response) {
	        window.location = "<?=$current_url?>";
	      });
	      FB.Event.subscribe('auth.logout', function(response) {
	    	  //window.location = "<?=$current_url?>";	 
	      });
	    } 

      function logoutClicked(){
  	  	<?
      	/*
  	   	$logoutUrlParams = array( 'next' => $current_url );
  	   	$logoutUrl = $facebook->getLogoutUrl($logoutUrlParams);
  	  	//window.location = "<?=$logoutUrl?>";
    	  FB.logout(function(response) {
	  	  	window.location = "<?=$current_url?>?logout=true";
    	  });  	  	
  	  	//window.location = "http://www.google.com";
    	  */
  	  	?>            		
    	  return false;     
	    }    
	  </script>
	
	  </script>
		<div id="wrap">
			<div class="sombra-top-wrap">
			</div>
			<div id="header">
				<a href="<?=$contexto?>"><h2>Votamos Todos democracia participativa online</h2></a>
				<div id="menu">
					<a href="<?=$menu_button1_url?>">
						<div class="boton-conocenos">
							<?=$menu_button1_label?>
						</div>
					</a>
					<?
					/*
					<a href="#" onclick="FB.login(null, {scope: 'email'});return false;" id="auth-loggedout">
						<div class="boton-login">
							login
              </div>							
						</div>
					</a>
					*/
					?>
					<div onclick="return false;" id="auth-loggedout">
						<div class="boton-login">
							<div class="fb-login-button" scope="email" data-show-faces="false" size="large" data-width="250" data-max-rows="1">
              </div>
						</div>
					</div>					
				</div>
        <div class="sinLogin" id="ayuda-login">
        	<div class="ingresarConFacebook">
          	Ingres&aacute; ahora usando tu cuenta de facebook
          </div>
				</div>				
				<!--                **********  ESTE DIV SE OCULTA CUANDO NO HICISTE LOGIN  **********   -->
				<div class="loguineado" id="auth-loggedin" style="display:none">
					<img id="auth-displayimage" width="50" height="50" />
					<div class="nombreLogin" id="auth-displayname">						
					</div>
					<!-- 
					<a href="#" onclick="logoutClicked();">
						LOGOUT
					</a>
					 -->
				</div>
				<!--                **********  ******************************************  **********   -->
				<img src="<?=$contexto?>img/divisor.jpg" width="984" height="3" />
			</div>
      
