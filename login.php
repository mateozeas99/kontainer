<?php
	require_once 'core/init.php';
	//include 'paginas/header.php';
	if (Input::exists()) {
		if (Token::check(Input::get('token'))) {
			$validate = new Validate();

			$validation = $validate->check($_POST,array(
				'username' => array('required' => true),
				'password' => array('required' => true)
			));
			if ($validation->passed()) {
				//DATA
				$fields = array("username"=>Input::get('username'),"password"=>Input::get("password"),"remember"=>Input::get("remember"));
				$fields_string = json_encode($fields);
				//URL
				$url = "http://localhost:8080/api/v1/login";
				//SEND REQUEST
				$client = curl_init($url);
				curl_setopt($client,CURLOPT_POST, 1);
				curl_setopt($client,CURLOPT_POSTFIELDS, $fields_string);
				curl_setopt($client,CURLOPT_RETURNTRANSFER,true);
				//RESPONSE
				$response = curl_exec($client);
				$code = curl_getinfo($client);
				//CLOSE CONNECTION
				curl_close($client);
				//WORK WITH DATA
				if($code['http_code'] !== 200)
				{
					//HANDLE THE ERROR
					if($code['http_code'] === 204)
					echo '<br><div class="row text-center"> Usuario o Password Invalidos</div>';
					if($code['http_code'] === 404)
					echo '<br><div class="row text-center"> Error: Por favor comunicarse al 0993862583</div>';
				}
				else
				{
						$json=json_decode($response);
						$sessionName = Config::get('session/session_name');
						$apiKeyName = Config::get('session/api_key_name');
						Session::put($sessionName, $json->id);
						Session::put($apiKeyName, $json->api_key);
						Redirect::to('index.php');
				}
			}
			else
			{
				foreach ($validation->errors() as $error) {
					echo $error .'<br>';
				}
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
  <title>Tranvia</title>
  <link href="css/fonts.googleapis.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="bower_components/materialize/dist/css/materialize.min.css"  media="screen,projection"/>
  <link type="text/css" rel="stylesheet" href="css/style.css"/>
      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>
<header>
  <nav>
    <div class="nav-wrapper amber">
      <a href="index.php" class="brand-logo left"><img src="img/logo.png" width="400" height="65"></a>
    </div>
  </nav>
</header>
<main>
<div class="valign-wrapper">
  <div class="row center">
    <form class="col s12" action="" method="POST">
      <div class="row">
        <div class="input-field col s12">
          <!--<i class="material-icons prefix">account_circle</i>-->
          <input id="username" type="text" name="username" class="validate">
          <label for="username">Username</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <!--<i class="material-icons prefix">security</i>-->
          <input id="password" type="password" name="password" class="validate">
          <label for="password">Password</label>
        </div>
      </div>
      <div class="row">
        <input type="checkbox" class="filled-in" id="remember" checked="checked" />
        <label for="remember">Remember</label>
        <input type="hidden" name="token" value="<?php echo Token::generate();?>">
      </div>
      <div class="row">
        <button class="btn waves-effect waves-light blue amber" type="submit" name="action">Login
          <!--<i class="material-icons">send</i>-->
        </button>
      </div>
      <div class="row">
        <a href="pages/forgotPassword.php">Forgot Password</a>
      </div>
    </form>
  </div>
</div>
</main>
<footer class="page-footer amber">
  <div class="footer-copyright">
    <div class="container center">
    © 2014 Copyright
    </div>
  </div>
</footer>

<!--Import jQuery before materialize.js-->
      <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
      <script type="text/javascript" src="bower_components/materialize/dist/js/materialize.min.js"></script>
</body>

