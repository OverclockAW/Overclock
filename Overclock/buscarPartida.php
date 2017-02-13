<?php
  namespace xampp\htdocs\Overclock;
  require_once __DIR__.'/includes/config.php';

  if($app->usuarioLogueado() && $app->rolUsuario() == "Usuario" && isset($_POST["email"])) {
	if(isset($_SESSION["partida"])){ // Si ya tiene una partida asignada, nos ahorramos buscarla y directamente la devolvemos
		$result = array();
		$result["ok"] = true;
		$result["p"] = $_SESSION["partida"];
	}
	else{ // Si no tiene ninguna asignada, la buscamos*/
		$result = Partida::buscar($_POST['email']);
		if($result["ok"] == true){
			$app->partida($result["p"]);
		}
	}
	header('ContentType::application/json; charset=utf-8');
	echo json_encode($result);
  }
  else{
	  
	$mensajeError;
	if(!isset($_SESSION["partida"])){
		$mensajeError = "No tienes ninguna partida en curso";
	}
	
	else if ($app->rolUsuario() != "Usuario"){
		$mensajeError = "No puedes acceder a una partida si no eres usuario registrado";
	}
	
	else if (!isset($_POST["email"])){
		$mensajeError = "No puedes acceder a una partida si no estás conectado";
	}
	
	$result = array();
	$result["ok"] = false;
	$result["p"] = $mensajeError;
	echo json_encode($result);
	http_response_code(400);
	die();
  }
?>
