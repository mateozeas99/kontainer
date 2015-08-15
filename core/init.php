<?php
session_start();
$GLOBALS['config'] = array(
	'mysql'=> array(
			'host' => 'localhost',
			'username' => 'kontainer',
			'password' => 'maradona68',
			'db' => 'kontainer'
		),
	'remember'=>array(
		'cookie_name'=> 'hash',
		'cookie_expiry'=> 604800
		),
	'session' => array(
		'session_name'=>'user',
		'token_name' => 'token',
		'api_key_name' => 'api_key'
		)
	);
spl_autoload_register(function($class){
	require_once 'classes/'.$class.'.php';
});

require_once 'functions/sanitize.php';

if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('TUSERS_SESSIONS',array('HASH','=',$hash));

	if ($hashCheck->count()) {
		$user = new User($hashCheck->first()->USER_ID);
		$user->login();
	}
	
}
?>
