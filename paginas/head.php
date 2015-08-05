  <head>
    <meta charset="utf-8" />
    <title>Tranvía-Sináptica</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link type="text/css" rel="stylesheet" href="bower_components/materialize/dist/css/materialize.min.css"  media="screen,projection"/>
	<link type="text/css" rel="stylesheet" href="css/style.css"/>

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      	<script>
	<?php
		$session = Session::get(Config::get('session/session_name'));
		$apiKey = Session::get(Config::get('session/api_key_name'));
		$user=new User($session);
		$uname=$user->data()->USERNAME;
		echo 'var apiKey = \''.$apiKey.'\';';
		echo 'var user = \''.$uname.'\';'; 
	?>
	</script>
  </head>
