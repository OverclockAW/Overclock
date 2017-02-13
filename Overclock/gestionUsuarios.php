<?php require_once __DIR__.'/includes/config.php'; ?>
<!DOCTYPE html>
<html>

	<head>
		<title>Gestión de usuarios</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css')?>"/>
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/gestionUsuarios.css')?>"/>
		<script type="text/javascript" src="<?= $app->resuelve('/js/jquery-1.9.1.min.js')?>"></script>		
		<script type="text/javascript" src="<?= $app->resuelve('/js/gestionUsuarios.js')?>"></script>	
	</head>

	<body>
		<?php if($app->usuarioLogueado() && $app->rolUsuario() == "Administrador"){ // Mejor en compruebaPermisos?>
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
										<form action="gestionUsuarios.php" method="post">
											<input type="text" name="texto" class="textoBusqueda" value=""/>
											<select name="filtrado">
												<option>email</option>
												<option>usuario</option>
												<option>rol</option>
												<option>baneado</option>
											</select>
											<button id="botonFiltrar" type="submit"> Filtrar </button>
										</form>
										<a href="<?= $app->resuelve('/registro.php')?>"> <button id="admin">Nuevo administrador</button> </a>
									</div>
									<table id = "usuarios">
										<thead id = "cabeceraTabla"><tr id = "cab">
											<th class="nombreColumna">NOMBRE</th>
											<th class="nombreColumna">CORREO</th>
											<th class="nombreColumna">ROL</th>
											<th class="celdasCuadradas">BANEADO</th>
											<th class="celdasCuadradas"></th>
											<th class="celdasCuadradas"></th>
											<th class="celdasCuadradas"></th>	
										</tr></thead>
										<tbody>
										<?php	 
											$contador = 1;
											if(isset($_POST['filtrado']) && isset($_POST['texto']))
												$usuarios = \xampp\htdocs\Overclock\ Usuario::filtrarUsuarios($_POST['filtrado'], $_POST['texto']);
											else
												$usuarios = \xampp\htdocs\Overclock\ Usuario::filtrarUsuarios('','');
											foreach($usuarios as $u){ 
												if($u->email() != $app->emailUsuario()){
										?>
											<tr id="<?= $contador ?>">
												<td class = "celdaNormal"> <?= $u->usuario(); ?> </td>
												<td class = "celdaNormal"> <?= $u->email(); ?> </td>
												<td class = "celdaNormal"> <?= $u->rol(); ?> </td>
												<td class = "celdasCuadradas" id="ban_<?= $contador?>"> <?= $u->baneado(); ?> </td>
												<td class = "celdasCuadradas">
													 <input type="image" onclick="banear('<?= $contador ?>','<?= $u->email() ?>')" src="<?= $app->resuelve('/img/banear.png')?>">
												</td>
												<td class = "celdasCuadradas">
													 <input type="image" onclick="borrar('<?= $contador ?>','<?= $u->email(); ?>')" src="<?= $app->resuelve('/img/eliminar.png')?>">
												</td>
											</tr>
										<?php $contador++;
												} 
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
