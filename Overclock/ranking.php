<?php require_once __DIR__.'/includes/config.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Ranking</title>
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
			<div id="marcoAdministracion">
				<div id = "bloqueAdministracion">
					<table id = "tablaRanking">
						<thead id = "cabeceraTabla"><tr id = "cab">
						<th class="nombreColumna">USUARIO</th>
						<th class="nombreColumna">JUGADAS</th>
						<th colspan="2" class="colspan2">BALANCE</th>
						</tr></thead>
						<?php
							$usuarios = \xampp\htdocs\Overclock\ Usuario::filtrarUsuarios('','');
							
							foreach($usuarios as $u) {
						?>
							<tr>
								<td class = "celdaNormal"><?=$u->usuario()?></td>
								<td class = "celdaNormal"><?=$u->ganadas() + $u->perdidas()?></td>
								<td class = "celdaNormal"><?=$u->ganadas()?></td>
								<td class = "celdaNormal"><?=$u->perdidas()?></td>
							</tr>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
      </div>
    </div>
  </div>
  </div>
</body>
</html>
