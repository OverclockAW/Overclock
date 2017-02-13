<?php
	use xampp\htdocs\Overclock;
	$app = Overclock\Aplicacion::getSingleton();

	function estaLogueado(){
		$app = Overclock\Aplicacion::getSingleton();
		return $app->usuarioLogueado();
	}

	function mostrarSaludo(){
		$html = '';
		$app = Overclock\Aplicacion::getSingleton();
		$nombreUsuario = $app->nombreUsuario();
		if ($app->usuarioLogueado()) {
			$rol = $app->rolUsuario();
			if($rol == 'Administrador')
				$html = '<h2> Panel de administración </h2>';
			else
				$html = "<h2> Bienvenido, ".$nombreUsuario."</h2>";
		}
		return $html;
	}
?>

<div id = "panelIzquierdo">
	<div id = "cosasPanelIzquierdo">
		<?php if(!estaLogueado()){
			$formLogin = new \xampp\htdocs\Overclock\FormularioLogin();
			$formLogin->gestiona();
		?>
				<a href ="<?= $app->resuelve('/registro.php')?>"><button class = "botonesPanelIzquierdo" id = "detalles">Registrarse</button></a>
		<?php }
			else{
				echo mostrarSaludo();
		?>
		<a href = "modificarCuenta.php"><button class = "botonesPanelIzquierdo" id = "indice">Mi cuenta</button></a>
		<a href = "miCuenta.php"><button class = "botonesPanelIzquierdo" id = "indice">Principal</button></a>
		<a href = "logout.php"><button class = "botonesPanelIzquierdo" id = "detalles">Desconectar</button></a>
		<?php } ?>

	</div>
	<a href ="<?= $app->resuelve('/ranking.php')?>"><img id = "caliz" src = "<?=$app->resuelve('img/caliz.png')?>"></img><button id = "ranking">
	</button>
	</a>

</div>
