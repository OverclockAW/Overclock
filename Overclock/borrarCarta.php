<?php
	namespace xampp\htdocs\Overclock;
	require_once __DIR__.'/includes/config.php';
	if($app->usuarioLogueado() && $app->rolUsuario() == "Administrador" && isset($_POST['nombre']))
		$ok = Carta::borrarCarta($_POST['nombre']);
	else {
		$result = array();
		$result["ok"] = false;
		$result["resultAccion"] = "Ha habido un error al ejecutar la acción.";
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
		http_response_code(403);
		die();
		header('Location: '.$app->resuelve('/index.php'));
	}
?>