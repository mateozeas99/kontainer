<?php
require_once 'core/init.php';
$user = new User();
if ($user->isLoggedIn())
{
	include 'paginas/header.php';
?>
	<div class="container border_sinaptica">
      <div class="row">
        <div class="col s12 m6 l4">
          <div class="card white hoverable">
            <div class="card-content center-align">
	        	<a href="dashboard.php">
	      			<img src="img/humidity.png" width="64" >
	      		</a>
	      		<br>
              	<span class="card-title black-text">Dashboard</span>
              	<p>Controladores/Sensores</p>
            </div>
            <div class="card-action center-align">
            	<a href="dashboard.php" class="waves-effect white-text btn amber">Ingresar</a>
            </div>
          </div>
        </div>
        <!--<div class="col s12 m6 l4">
          <div class="card white hoverable">
            <div class="card-content center-align">
	            <a href="camera.php">
	          		<img src="img/camera.png" width="64">
				      </a>
				      <br>
              	<span class="card-title black-text">Cámaras</span>
              	<p>Cámaras</p>
            </div>
            <div class="card-action center-align">
            	<a href="camera.php" class="waves-effect white-text btn amber">Ingresar</a>
            </div>
          </div>
        </div>-->
        <div class="col s12 m6 l4">
          <div class="card white hoverable">
            <div class="card-content center-align">
              <a href="configurations.php">
                <img src="img/settings.png" width="64">
        </a>
        <br>
                <span class="card-title black-text">Configuración</span>
                <p>Usuarios/Contraladores/Grupos</p>
            </div>
            <div class="card-action center-align">
              <a href="configurations.php" class="waves-effect white-text btn amber">Ingresar</a>
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
