<?php require_once __DIR__.'/includes/config.php'; ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Cartas</title>
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css')?>"/>
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/cartas.css')?>"/>
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
							<?php
								$cartas = xampp\htdocs\Overclock\ Carta::filtrarCartas('', '');
								
								foreach($cartas as $c){ 
									$marcoCarta = $c->getMarco();
							 ?>
								<div id = "contenedor">
									<div class = "carta">
										<img class = "imagenCarta" src="<?=$app->resuelve('/img/cartas/'.$c->getImagen().'.png')?>"></img>
										<img class = "marcoCarta" src = "<?=$marcoCarta?>"></img>
									</div>
									<div id = "descripcionCarta">
										<p><em>Nombre:</em> <?=$c->getNombre()?></p>
										<p><em>Tipo de carta:</em> <?=$c->getTipo()?></p>
										<?php
										if($c->getHp() != -1){ ?>
											<p><em>Vida:</em> <?=$c->getHp()?></p>
											<p><em>Energía:</em> <?=$c->getEnergia()?></p>
										<?php
										}
										?>
										<p><em>Descripción:</em> <?=$c->getDescripcion()?></p>
									</div>
								</div>
						<?php 	}							
							
						?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>