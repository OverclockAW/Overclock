<?php
  namespace xampp\htdocs\Overclock;
  require_once __DIR__.'/includes/config.php';

  if($app->usuarioLogueado() && $app->rolUsuario() == "Usuario" && isset($_SESSION['email'])) {
    Partida::rendirse($_SESSION['email']);
	
  }
?>
