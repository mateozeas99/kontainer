<?php
require_once 'core/init.php';
$user = new User();
if ($user->isLoggedIn() && $user->hasPermission('admin'))
{
	include 'paginas/header.php';
?>
<div class="container border_sinaptica">
  <div class="row">
    <div class="col s12 m8 l8 offset-m2 offset-l2">
      <div class="card white hoverable">
        <div class="card-content center-align">
          <i class="material-icons medium">account_circle</i>
        <br>
            <span class="card-title black-text">Nuevo Usuario</span>
            <form class="col s12">
              <div class="row">
                <div class="input-field col s12 m6 l6 offset-m3 offset-l3">
                  <input id="username" type="text" class="validate" autofocus>
                  <label for="username">Username</label>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m6 l6">
                  <input id="first_name" type="text" class="validate">
                  <label for="first_name">Nombre</label>
                </div>
                <div class="input-field col s12 m6 l6">
                  <input id="last_name" type="text" class="validate">
                  <label for="last_name">Apellido</label>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m6 l6">
                  <input id="password" type="password" class="validate">
                  <label for="password">Password</label>
                </div>
                <div class="input-field col s12 m6 l6">
                  <input id="password_again" type="password" class="validate">
                  <label for="password_again">Repeat Password</label>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12 m6 l6">
                  <input id="email" type="email" class="validate">
                  <label for="email">Email</label>
                </div>
              </div>
            </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
	include 'paginas/footer.php';
}
else
{
	include 'login.php';
}
?>
<script type="text/javascript">
<?php
if ((substr($_SERVER['REMOTE_ADDR'],0,11) == "192.168.0.") || ($_SERVER['REMOTE_ADDR'] == "127.0.0.1")){
        echo 'var ip = "192.168.0.100";';
}else{
        echo 'var ip = "kontainer.servehttp.com";';
}
?>
$( document ).ready(function() {
    // Handler for .ready() called.
    $(".button-collapse").sideNav();
    $('.collapsible').collapsible({
      accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });
    //connectWebSocket(ip);
  });
</script>