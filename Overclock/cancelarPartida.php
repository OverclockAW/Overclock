<?php
  namespace xampp\htdocs\Overclock;
  require_once __DIR__.'/includes/config.php';

  if($app->usuarioLogueado() && $app->rolUsuario() == "Usuario" && isset($_POST['email'])) {
	if(!isset($_SESSION["partida"]) || $_SESSION["partida"] == false){ 
		Partida::eliminarBusqueda($_POST['email']);
	}
  }
?>
