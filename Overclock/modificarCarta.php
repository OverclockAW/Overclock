<?php require_once __DIR__.'/includes/config.php'; ?>
<!DOCTYPE html>
<html>

	<head>
		<title>Gestión de cartas</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css')?>"/>
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/gestionUsuarios.css')?>"/>
		<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript" src="js/modificarCarta.js"></script>
	</head>

	<body>
		<?php if($app->usuarioLogueado() && $app->rolUsuario() == "Administrador"){ ?>
		<div id = "capaMadre">
			<?php $app->doInclude('comun/banner.php'); ?>
			<div id = "cuerpo">
				<div id = "botones">
					<?php $app->doInclude('comun/sidebar.php'); ?>
					<div id = "panelDerecho">
						<?php $app->doInclude('comun/enlaces.php'); ?>
						<div id = "descripcion">
							<div id = "marcoAdministracion">
						<div id= "bloqueFormulario">
							<p> Modifica algun parametro de la carta para mandar el formulario </p>
							<?php 
								$formModificarCarta = new \xampp\htdocs\Overclock\FormularioModificarCarta(); 
								$formModificarCarta->gestiona(); 
							?>
						</div>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } else header('Location: '.$app->resuelve('/index.php')); ?>	
	</body>
	
</html>