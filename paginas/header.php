<!DOCTYPE html>
<html lang="es">
	<?php 	include 'paginas/head.php'; 
			require_once 'core/init.php';
			$user = new User();
	?>
  <body>
	<header>
		<!-- Dropdown Structure -->
		<ul id="dropdown1" class="dropdown-content">
		  <li><a href="perfil.php">Perfil</a></li>
		  <li class="divider"></li>
		  <li><a href="logout.php">Salir</a></li>
		</ul>

  	<div class="navbar-fixed">
	  <nav>
	    <div class="nav-wrapper  blue darken-3">
	    	<a href="index.php" class="brand-logo center"><span>Sináptica</span></a>
	    	<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
	  		<ul class="right hide-on-med-and-down">
	  			<?php if($user->hasPermission('admin')) echo '<li><a href="config.php"><i class="material-icons left">settings</i>Configuración</a></li>'; ?>
	      		<!-- Dropdown Trigger -->
	      		<li><a class="dropdown-button" href="#!" data-activates="dropdown1"><i class="material-icons left">account_circle</i><?php echo escape($user->data()->USERNAME);?></a></li>
	    	</ul>
	    	<ul class="side-nav collapsible" id="mobile-demo" data-collapsible="accordion">
	    		<li>
			      <div class="collapsible-header black-text"><i class="material-icons">account_circle</i><?php echo escape($user->data()->USERNAME);?></div>
			      <div class="collapsible-body">
			      	<a href="perfil.php">Perfil</a>
			      	<a href="logout.php">Salir</a>
			      </div>
			    </li>
	    	</ul>
	    </div>
	  </nav>
	</div>
	</header>
	<main>
		<br>
	<div class="valign-wrapper">