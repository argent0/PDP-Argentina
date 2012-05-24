<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head profile="http://gmpg.org/xfn/11">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>FindSingles</title>
	
	<link rel="stylesheet" href="resources/style.css" type="text/css" media="screen">
	<link rel="stylesheet" href="resources/a.css" type="text/css" media="screen">
	<!-- <link rel="alternate" type="application/rss+xml" title="PhotoSquares2 RSS Feed" href="http://functionthemes.com/demo/photosquares2/feed/">  -->
	<link rel="stylesheet" id="fancy_style-css" href="resources/jquery.css" type="text/css" media="all">
	<link rel="stylesheet" id="ft-aino-classic-css" href="resources/galleria.css" type="text/css" media="all">

	<!-- <script type="text/javascript" src="resources/l10n.js"></script> -->
	<script type="text/javascript" src="resources/jquery_003.js"></script>
	<script type="text/javascript" src="resources/jquery.js"></script>
	<script type="text/javascript" src="resources/jquery_002.js"></script>
	<!-- <script type="text/javascript" src="resources/main.js"></script> -->
	<script type="text/javascript" src="resources/galleria-1.js"></script>
	<!-- <script type="text/javascript" src="resources/comment-reply.js"></script> -->

	<!-- 
	<link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://functionthemes.com/demo/photosquares2/xmlrpc.php?rsd">
	<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="http://functionthemes.com/demo/photosquares2/wp-includes/wlwmanifest.xml">
	<link rel="index" title="PhotoSquares2" href="http://functionthemes.com/demo/photosquares2/">
	 -->

	<script type="text/javascript">
		// <![CDATA[
		var $ft = jQuery.noConflict();
		$ft(document).ready(function($){

			/*RESIZE SINGLE IMAGES */

			$max_width=984;

			Xpos = ($('.post').length>0)?$('.post').offset():0;
			$( ".post img" ).load(
			function(){
				$width=$(this).attr("width");
				if($width>$max_width){
					$(this).attr("width",$max_width-15)
					.removeAttr("height");
				}
			}
			);
			$( ".post img" ).each(
			function(){
				$width=$(this).attr("width");
				if($width>$max_width){
					$(this).attr("width",$max_width-15)
					.removeAttr("height")
				}

			}
			)


			/* resizes featured image */
			var loadingdiv = $('#featured_loading');
			$('img.ps_featured').hide()
			$('img.ps_featured').load(function(){
				var $max_width=884;
				$width=$(this).width();
				$('#featured_wrap').css('width',$width);
				if($width>$max_width){
					$(this).attr("width",$max_width)
					.removeAttr("height");
					$('#featured_wrap').css('width',$max_width);
				}
				Xpos = $('.post').offset();
				loadingdiv.hide();
				$('img.ps_featured').show()

			})
			.each(function(){
				if(this.complete || (jQuery.browser.msie && parseInt(jQuery.browser.version) == 6))
				$(this).trigger("load");
			});


			/* smooth scrolling to top */
			$("a[href='#top']").click(function() {
				$("html,body").animate({ scrollTop: 0 }, 400);
				return false;
			});

			$("a[href='#to_content']").click(function() {
				$("html,body").animate({ scrollTop: Xpos.top }, 400);
				return false;
			});


			// Centering the gallery thumbnails
			object = jQuery('.gallery');
			thumbnail = jQuery('.gallery img').width();
			if(object.length > 0)
			{
				string = object.attr('class');
				string = string.split(' ');
				string = parseInt(string[2].substring(16,17)) * (thumbnail + 10);
				object.css('width',string+'px');
			}



			//Gallery & SlideShow install scripts
			$('.ft-aino-images').css({'width':'884px','height':'663px'});
			Galleria.loadTheme('http://functionthemes.com/demo/photosquares2/wp-content/themes/photosquares2/theme/js/aino-g/themes/classic/galleria.classic.js')
			$('.ft-aino-images').galleria({
				width: 884,
				height: 663,
				show_counter: false,
				thumbnails: true,
				show_imagenav: true,
			showInfo: true	});

		}); /* end */

		/*
		jQuery(window).load(function () {
			jQuery('#demo').masonry({
				singleMode: true,
				itemSelector: '.box'
			});
			// Centering social buttons vertically
			//jQuery('#socials').css('margin-top',jQuery('#header-wrap').height()/2 - 16 + 'px').fadeIn(1000);
		});
		*/


		// ]]>
	</script>
	<link rel="stylesheet" type="text/css" href="resources/css.css">
	<script async="async" src="resources/galleria.js"></script>
</head>
<body>
	<div id="container" style="width: 984px;">
		<div id="top">
			<div id="header-wrap">
				<div id="blog_name">
					<h1 id="blog_title"><a href="http://functionthemes.com/demo/photosquares2">Find Singles on Facebook</a></h1>
				</div>
			</div>
		</div>
		<div id="content">
			<div style="position: relative; height: 1296px;" class="contcenter wrap masoned" id="demo">
<?php 
ini_set("display_errors", "0");
//error_reporting(E_ALL);
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR );

include("configuracion.php");
include("already_fetched_map_utils.php");

flush();

// El codigo que te da facebook para poder pedir un access_token
// La primera vez que se entra a esta pagina no viene el code.
$code = $_REQUEST["code"]; 



// La url a la que facebook tiene que redirigir una vez que el usuario permitió usar la aplicacion Dharma   
$redirect_url = $site_url . "/facebook.php";

// La url a la que hay que pegarle para que facebook te de un access token
$token_url = "https://graph.facebook.com/oauth/access_token?client_id="
    . $facebook_app_id . "&redirect_uri=" . urlencode($redirect_url) . "&client_secret="
    . $facebook_app_secret . "&code=" . $code;

$access_token = file_get_contents($token_url);
$access_token = substr($access_token, 0, strrpos($access_token, '&'));

// Consigo los datos del usuario de facebook
$me_url = "https://graph.facebook.com/me?" . $access_token;    
$me_json = file_get_contents($me_url);
$me = json_decode($me_json);
$me_gender = $me->gender;
$me_gender_other = $me_gender=="male" ? "female" : "male";


/*
echo $access_token . "<br/>";
echo "<br/>";

echo $user->id . "<br/>";
echo $user->name . "<br/>";
echo $user->first_name . "<br/>";
echo $user->last_name . "<br/>";
echo $user->link . "<br/>";
echo $user->gender . "<br/>";
echo $user->email . "<br/>";
echo $user->timezone . "<br/>";
echo $user->locale . "<br/>";
echo "<br/>";
echo "<br/>";
*/

// echo "<style type='text/css'>
// 				div {
// 					border: medium solid;
// 					float: left;
// 					margin: 50px;
// 				}
// 			</style>";

session_start();
create_already_fetched_map();
//$already_fetched_map = array();
set_value_already_fetched_map($me->id, $me);
//$already_fetched_map[$me->id] = $me;


$friends_url = "https://graph.facebook.com/fql?q=SELECT uid, name, pic_square, sex,relationship_status FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me()) and sex = '$me_gender_other' and relationship_status='Single'&" . $access_token;
$friends_url = str_replace(" ", "%20",$friends_url);
$friends_json = file_get_contents($friends_url);
//echo "$friends_json <br/>";
$result = json_decode($friends_json);
$friends = $result->data;

foreach ($friends as $friend) {	
	//$user_url = "https://graph.facebook.com/$friend->uid?" . $access_token;    
	//$user_json = file_get_contents($user_url);
	//$user = json_decode($user_json);
	//echo $user_json . "<br/>";
	echo "<div class='box imgbox' id='post19' style='width: 300px; height: 398px; float:left'>";
	echo "	<a class='thumb-link' href='https://www.facebook.com/$friend->uid'>";
	echo "		<img class='mini' title='This Post is for Demonstration' alt='This Post is for Demonstration' src='https://graph.facebook.com/$friend->uid/picture?type=large'>";
	echo "	</a>";
	echo "	<div class='post_title'>";
	echo "		<a href='https://www.facebook.com/$friend->uid'>";
	echo "			$friend->name";
	echo "		</a>";
	echo "	</div>";
	echo "	<div class='post_title'>";
	echo "		<a href='https://www.facebook.com/$friend->uid'>";
	echo "			Is your friend";
	echo "		</a>";
	echo "	</div>";
	echo "	<div class='post_title'>";
	echo "		<a href='https://www.facebook.com/$friend->uid'>";
	echo "			Is single";
	echo "		</a>";
	echo "	</div>";				
	echo "</div>";
	
	/*
	echo "<div>";
	echo "<a href='https://www.facebook.com/$friend->uid'>" . $friend->name . "</a><br/>";
	echo "Is your friend<br/>";
	echo "Is single<br/>";
	$picture_url = "https://graph.facebook.com/$friend->uid/picture?type=large";
	echo "<img src='$picture_url'>" . "<br/>";
	echo "</div>";
	*/
	
	//$picture_json = file_get_contents($picture_url);
	//$result = json_decode($picture_json);
	//echo "" . "<br/><br/>";
	
	flush();
	set_value_already_fetched_map($friend->uid, $friend);
	//$already_fetched_map[$friend->uid] = $friend;
}

//echo "<br/><br/>" . "--------------------------------------------------" . "<br/><br/>";

$friends_url = "https://graph.facebook.com/fql?q=SELECT uid, name, pic_square, sex, relationship_status FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = me())&" . $access_token;
$friends_url = str_replace(" ", "%20",$friends_url);
$friends_json = file_get_contents($friends_url);
//echo "$friends_json <br/>";
$result = json_decode($friends_json);
$friends = $result->data;

foreach ($friends as $friend) {
	if (!isset_value_already_fetched_map($friend->uid)) {
	//if (!isset($already_fetched_map[$friend->uid])) {
		//$user_url = "https://graph.facebook.com/$friend->uid?" . $access_token;
		//$user_json = file_get_contents($user_url);
		//$user = json_decode($user_json);
		//echo $user_json . "<br/>";		
		if($friend->relationship_status == null && $friend->sex == $me_gender_other){
			echo "<div class='box imgbox' id='post19' style='width: 300px; height: 398px; float:left'>";
			echo "	<a class='thumb-link' href='https://www.facebook.com/$friend->uid'>";
			echo "		<img class='mini' title='This Post is for Demonstration' alt='This Post is for Demonstration' src='https://graph.facebook.com/$friend->uid/picture?type=large'>";
			echo "	</a>";
			echo "	<div class='post_title'>";
			echo "		<a href='https://www.facebook.com/$friend->uid'>";
			echo "			$friend->name";
			echo "		</a>";
			echo "	</div>";
			echo "	<div class='post_title'>";
			echo "		<a href='https://www.facebook.com/$friend->uid'>";
			echo "			Is your friend";
			echo "		</a>";
			echo "	</div>";
			echo "</div>";
			
			/*
			echo "<div>";
			echo "<a href='https://www.facebook.com/$friend->uid'>" . $friend->name . "</a><br/>";
			echo "Is your friend<br/>";
			$picture_url = "https://graph.facebook.com/$friend->uid/picture?type=large";
			echo "<img src='$picture_url'>" . "<br/>";
			//echo "Relationship status: " . $friend->relationship_status . "<br/>";
			//$picture_json = file_get_contents($picture_url);
			//$result = json_decode($picture_json);
			//echo "" . "<br/><br/>";
			echo "</div>";
			*/
			
			flush();
		}
		set_value_already_fetched_map($friend->uid, $friend);
		//$already_fetched_map[$friend->uid] = $friend;	
	}
}


//echo "<br/><br/>" . "--------------------------------------------------" . "<br/><br/>";

/*
$fql_multiquery_url = "https://graph.facebook.com/fql?q={"
. "'all_friends':'SELECT uid2 FROM friend WHERE uid1=me()',"
. "'photos_owned_by_friends':'SELECT object_id FROM photo_tag WHERE subject IN (SELECT uid2 FROM #all_friends)'"  
. "}&" . $access_token;
$fql_multiquery_url = str_replace(" ", "%20",$fql_multiquery_url);
$fql_multiquery_result = file_get_contents($fql_multiquery_url);
$fql_multiquery_obj = json_decode($fql_multiquery_result, true);
*/


$friends_url = "https://graph.facebook.com/me/friends?" . $access_token;	
$friends_json = file_get_contents($friends_url);
$result = json_decode($friends_json);
$friends = $result->data;

$json_encoded_friends = json_encode($friends);


echo "		
    <script>
			var access_token = '$access_token';  
			var me_gender_other = '$me_gender_other'; 
			var friends = $json_encoded_friends;
			friends = friends.slice(0,30); 
			
			function getFriendsOfFriends(){
				for (var index in friends) {
					jQuery.ajax({
		  			url: 'friends_of_friends.php?access_token=' + access_token + '&me_gender_other=' + me_gender_other + '&friend_id=' + friends[index].id,
		  			success: function(data) {
		    			jQuery('#demo').append(data);
		    			//jQuery('.imgbox').css({'left': '0px', 'top': '0px'});
		    			//jQuery('.imgbox').last().after(data);
		  			}
					});
				}	
			}
			
			jQuery(function() {
				getFriendsOfFriends();
			});			
		</script>";

$friends = array();

foreach ($friends as $friend) {	
		
	/*
	$user_url = "https://graph.facebook.com/$friend->id?" . $access_token;    
	$user_json = file_get_contents($user_url);
	$user = json_decode($user_json);
	if ($user->gender==$me_gender_other && $user->relationship_status=="Single") {
		echo $user_json . "<br/>";
	} else {
		echo $user->name . "<br/>";
	}
	flush();
	*/

  $photos_url = "https://graph.facebook.com/$friend->id/photos?" . $access_token;
	$photos_json = file_get_contents($photos_url);
	//echo $photos_json . "<br/>";
	$result2 = json_decode($photos_json);
	$photos = $result2->data;
	foreach ($photos as $photo) {
		$id = $photo->from->id;
		if (!isset_value_already_fetched_map($id)) {
		//if (!isset($already_fetched_map[$id])) {
			$user_url = "https://graph.facebook.com/$id?" . $access_token;
			$user_json = file_get_contents($user_url);
			$user = json_decode($user_json);
			if ($user->gender==$me_gender_other) {
				//&& $user->locale==$me->locale 
				// && $user->relationship_status=="Single") {

				$mutual_friends_url = "https://graph.facebook.com/me/mutualfriends/$id?" . $access_token;
				$mutual_friends_json = file_get_contents($mutual_friends_url);
				$mutual_friends = json_decode($mutual_friends_json);
				$mutual_friends_data = $mutual_friends->data;
				
				if (count($mutual_friends_data) > 1) {
					echo "<div class='box imgbox' id='post19' style='width: 300px; height: 398px; float:left'>";
					echo "	<a class='thumb-link' href='https://www.facebook.com/$user->id'>";
					echo "		<img class='mini' title='This Post is for Demonstration' alt='This Post is for Demonstration' src='https://graph.facebook.com/$user->id/picture?type=large'>";
					echo "	</a>";
					echo "	<div class='post_title'>";
					echo "		<a href='https://www.facebook.com/$user->id'>";
					echo "			$user->name";
					echo "		</a>";
					echo "	</div>";
					

					/*					
					echo "<div>";
					echo "<a href='https://www.facebook.com/$user->id'>" . $user->name . "</a><br/>";
					$picture_url = "https://graph.facebook.com/$user->id/picture?type=large";
					echo "<img src='$picture_url'>" . "<br/>";
					//echo $user_json . "<br/>";
					*/
					
					$mutual_count = 0;
					foreach ($mutual_friends_data as $mutual_friend) {
						$mutual_count++;
						if ($mutual_count<=3){
							//echo "Mutual Friend: $mutual_friend->name" . "<br/>";						
							echo "	<div class='post_title'>";
							echo "		<a href='https://www.facebook.com/$user->id'>";
							echo "			Mutual Friend: $mutual_friend->name";
							echo "		</a>";
							echo "	</div>";							
						}
					}
					//echo "" . "<br/><br/>";
					echo "</div>";
					
					//echo "</div>";
					
					flush();					
				}

			}
			set_value_already_fetched_map($id, $user);
			//$already_fetched_map[$id] = $user;
		}
		
		//$photo_url = $photo->source;
		//echo "<img src='$photo_url'>" . "<br/>";
		//flush();
	}

	/*
 	$friends2_url = "https://graph.facebook.com/$friend->id/friends?" . $access_token;
	$friends2_json = file_get_contents($friends2_url);
	//echo $friends2_json . "<br/>";
	$result2 = json_decode($friends2_json);
	$friends2 = $result2->data;
	foreach ($friends2 as $friend2) {	
		$user2_url = "https://graph.facebook.com/$friend2->id?" . $access_token;    
		$user2_json = file_get_contents($user2_url);
		$user2 = json_decode($user2_json);
		echo $user2_json . "<br/>";
		echo "bieeeen <br/>";
		flush();
	}
*/

//echo "<br/>";
//flush();
		
				
	/*
	echo $user->id . "<br/>";
	echo $user->name . "<br/>";
	echo $user->first_name . "<br/>";
	echo $user->last_name . "<br/>";
	echo $user->link . "<br/>";
	echo $user->gender . "<br/>";
	echo $user->email . "<br/>";
	echo $user->timezone . "<br/>";
	echo $user->locale . "<br/>";
	echo "<br/>";		
	*/
}

//$_SESSION['already_fetched_map'] = $already_fetched_map;

	
/*
	// Buscar album de Dharma

	$albums_url = "https://graph.facebook.com/me/albums?" . $access_token;
	$albums_json = file_get_contents($albums_url);
	$result = json_decode($albums_json);
	$albums = $result->data;
		
	$album_id = 0;
	// Get the new album ID

	foreach ($albums as $album) {	
		if ($album->name == $nombre_album) {
			$album_id = $album->id;
		}		
	}

	if ($album_id==0) {
		// Create a new album
		$album_url = "https://graph.facebook.com/me/albums?" . $access_token;
		
		$postdata = http_build_query( array('name' => $nombre_album, 
		                                    'message' => ''));
		$opts = array('http' => array ('method'=> 'POST',
		                               'header'=> 'Content-type: application/x-www-form-urlencoded',
		                               'content' => $postdata
		                               ));
		$context  = stream_context_create($opts);
		$result = json_decode(file_get_contents($album_url, false, $context));
		
		// Get the new album ID
		$album_id = $result->id;		
	}
	
	return $album_id;
}
*/
		
?>

				<div class="clearcontent"></div>
			</div>
			<div class="clearcontent"></div>
		</div>
	</div>
	
</body>
</html>