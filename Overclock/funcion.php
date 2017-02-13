<?php
  namespace xampp\htdocs\Overclock;
  require_once __DIR__.'/includes/config.php';

if($app->usuarioLogueado() && $app->rolUsuario() == "Usuario" && isset($_SESSION["partida"]) && isset($_POST["accion"])) {
	$accion = $_POST["accion"];
	$result = array();
	$result["ok"] = true;
	$id = $_SESSION["partida"];
	$p = new Partida($id);
	$nombreFuncion = $accion["nombre"];
	if($accion["llevaParams"] == "si"){
		$parametrosFuncion = $accion["parametros"];
		$result["resultAccion"] = $p->$nombreFuncion($parametrosFuncion);
		if($result["resultAccion"] == false)
			$result["ok"] = false;
	}
	
	else{
		$result["resultAccion"] = $p->$nombreFuncion();
		if($result["resultAccion"] == false)  // La función devolverá false si no deja hacer lo esperado. En otro caso, devuelve lo que se necesite. Se puede devolver lo que sea, pero molaría devolver un array con un mensaje en la primera posición que sería lo que se añade al log de partida, o el propio movimiento.
			$result["ok"] = false; // Esto es para luego construir el mensaje personalizado de error en la función tratarResultados de partida.js
	}
	
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
	//die();
	
}
else{
	$result = array();
	$result["ok"] = false;
	$result["resultAccion"] = "Ha habido un error al ejecutar la acción.";
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($result, JSON_UNESCAPED_UNICODE);
	http_response_code(400);
	die();
 }

?>