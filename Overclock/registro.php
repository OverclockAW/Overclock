<?php require_once __DIR__.'/includes/config.php'; ?>
<!DOCTYPE html>
<html>
  <head>
	<title>Registro</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css')?>"/>
	<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/miCuenta.css')?>"/>
	<script type="text/javascript" src="<?= $app->resuelve('/js/jquery-1.9.1.min.js')?>"></script>
	<script type="text/javascript" src="<?= $app->resuelve('/js/registro.js')?>"></script>
  </head>

  <body>
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
							<?php 
								$formRegistro = new \xampp\htdocs\Overclock\FormularioRegistro(); 
								$formRegistro->gestiona(); 
							?>
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	</body>

</html>
