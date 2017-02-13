<?php require_once __DIR__.'/includes/config.php'; ?>
<!DOCTYPE html>
<html>

	<head>
		<title>Index</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css')?>"/>
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
						<p>Nuestro proyecto es un juego de cartas online llamado Overclock (オーバークロック). El juego
						trata de sumergir a los jugadores en un entorno de rol dentro de un universo de ambientación
						de SteamPunk. Overclock diferencia los roles de ambos jugadores: uno de ellos encarna el
						papel de un aventurero que maneja un grupo de personajes, que asemeja el clásico grupo de
						héroes que se adentran en una mazmorra; mientras que el otro jugador toma el papel de
						dungeon master, controlando la mazmorra y a los jefes que en ella habitan.
						</p>
					</div>
				</div>
			</div>
		</div>
		</div>
	</body>

</html>
