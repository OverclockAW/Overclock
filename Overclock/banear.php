<?php
	namespace xampp\htdocs\Overclock;
	require_once __DIR__.'/includes/config.php';
	
	if($app->usuarioLogueado() && $app->tieneRol('Administrador', 'Acceso Denegado', 'No tienes permisos suficientes para administrar la web.') && isset($_POST['email'])){
		//echo json_encode('He entrado en Usuario::banearUsuario');
		echo Usuario::banearUsuario($_POST['email']);
	}else {
		$result = array();
		$result["ok"] = false;
		$result["logueado"] = $app->usuarioLogueado();
		$result["tieneRol"] = $app->tieneRol('Administrador', 'Acceso Denegado', 'No tienes permisos suficientes para administrar la web.');
		$result["rol"] = $_SESSION['rol'];
		$result["resultAccion"] = "Ha habido un error al ejecutar la acción.";
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode($result, JSON_UNESCAPED_UNICODE);
		http_response_code(403);
		die();
		header('Location: '.$app->resuelve('/index.php'));
	}
?>