<?php require_once __DIR__.'/includes/config.php'; ?>
<!DOCTYPE html>
<html>

	<head>
		<title>Gestión de cartas</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css')?>"/>
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/gestionUsuarios.css')?>"/>
		<script type="text/javascript" src="<?= $app->resuelve('/js/jquery-1.9.1.min.js')?>"></script>
		<script type="text/javascript" src="<?= $app->resuelve('/js/gestionCartas.js')?>"></script>
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
							<div id="marcoAdministracion">
								<div id = "bloqueAdministracion">
									<div id="find">
										<form action="gestionCartas.php" method="post">
											<input type="text" name="texto" class="textoBusqueda" value=""/>
											<select name="filtrado">
												<option>Nombre</option>
												<option>Hp</option>
												<option>Energia</option>
												<option>Tipo</option>
											</select>
											<button id="botonFiltrar" type="submit"> Filtrar </button>
										</form>
									</div>
									<table id = "cartas">
										<thead id = "cabeceraTabla"><tr id = "cab">
											<th class="nombreColumna">NOMBRE</th>
											<th class="nombreColumna">HP</th>
											<th class="nombreColumna">ENERGIA</th>
											<th class="celdaTipo">TIPO</th>
											<th class="celdasCuadradas"></th>
											<th class="celdasCuadradas"></th>
											<th class="celdasCuadradas"></th>	
										</tr></thead>
										<tbody>
										<?php	 
											$contador = 1;
											if(isset($_POST['filtrado']) && isset($_POST['texto']))
												$cartas = \xampp\htdocs\Overclock\ Carta::filtrarCartas($_POST['filtrado'], $_POST['texto']);
											else
												$cartas = \xampp\htdocs\Overclock\ Carta::filtrarCartas('','');
											foreach($cartas as $c){ 
										?>
										<tr id="<?= $contador ?>">
											<td class = "celdaNormal"> <?= $c->getNombre(); ?> </td>
											<td class = "celdaNormal"> <?= $c->getHp(); ?> </td>
											<td class = "celdaNormal"> <?= $c->getEnergia(); ?> </td>
											<td class = "celdaTipo"> <?= $c->getTipo(); ?> </td>
											<td class = "celdasCuadradas">
												<input type="image" onclick = "borrar('<?= $contador ?>','<?= $c->getNombre() ?>')" src="<?= $app->resuelve('/img/eliminar.png')?>" />
											</td>
											<td class = "celdasCuadradas"><a class = "botonesInternos" href="<?= $app->resuelve('/modificarCarta.php?nombre='.
												$c->getNombre().'&hp='.$c->getHp().'&energia='.$c->getEnergia().'&tipo='.$c->getTipo())?>">
												<img id = "eliminar" src = "img/modificar.png"><img></a>
											</td>
										</tr>
										<?php $contador++;
											}
										?>
										</tbody>
									</table>
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
