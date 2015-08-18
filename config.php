<?php
require_once 'core/init.php';
$user = new User();
if ($user->isLoggedIn() && $user->hasPermission('admin'))
{
	include 'paginas/header.php';
?>
<div class="container border_sinaptica">
  <div class="row">
    <div class="col s12">
      <ul class="tabs">
        <li class="tab col s4"><a class="active"href="#users">Usuarios</a></li>
        <li class="tab col s4"><a href="#modules">Módulos</a></li>
        <li class="tab col s4"><a href="#extras">Extras</a></li>
      </ul>
    </div>
    <div id="users" class="col s12">
      <div class="row">
        <div class="col s12 m8 l8 offset-m2 offset-l2">
          <div class="card white hoverable">
            <div class="card-content center-align">
              <i class="material-icons medium">account_circle</i>
            <br>
                <span class="card-title black-text">Usuarios</span>
                <table class="striped centered" id="tableUsers">
                  <thead>
                    <tr>
                      <th>Username</th>
                      <th class="hide-on-med-and-down">Name</th>
                      <th class="hide-on-med-and-down">Last</th>
                      <th class="hide-on-med-and-down">E-mail</th>
                      <th>Group</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="modules" class="col s12">
      <div class="row">
        <div class="col s12 m6 l4">
          <div class="card white hoverable">
            <div class="card-content center-align">
                Modulos
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="extras" class="col s12">
      <div class="row">
        <div class="col s12 m6 l4 offset-m4 offset-l4">
          <div class="card white hoverable">
            <div class="card-content center-align">
                <span class="card-title black-text">Extras</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
    <a class="btn-floating btn-large red">
      <i class="large material-icons">add</i>
    </a>
    <ul>
      <li><a href="newUser.php" class="btn-floating blue"><i class="material-icons">account_circle</i></a></li>
      <li><a href="addModule.php" class="btn-floating green"><i class="material-icons">dns</i></a></li>
    </ul>
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
    users();
    $(".button-collapse").sideNav();
    $('.collapsible').collapsible({
      accordion : false // A setting that changes the collapsible behavior to expandable instead of the default accordion style
    });
    //connectWebSocket(ip);
  });
</script>