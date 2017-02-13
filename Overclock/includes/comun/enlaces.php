<?php
	use xampp\htdocs\Overclock;
	$app = Overclock\Aplicacion::getSingleton();
?>
<div id = "enlaces">
	<a href = "<?= $app->resuelve('/index.php')?>"><button class = "botonesEnlaces" id = "inicio">Inicio</button></a>
	<a href = "<?= $app->resuelve('/sobreNosotros.php')?>"><button class = "botonesEnlaces" id = "info">Quienes somos</button></a>
	<a href = "<?= $app->resuelve('/reglas.php')?>"><button class = "botonesEnlaces" id = "reglas">Reglas</button></a>
	<a href = "<?= $app->resuelve('/historia.php')?>"><button class = "botonesEnlaces" id = "lore">Historia</button></a>
	<a href = "<?= $app->resuelve('/cartas.php')?>"><button class = "botonesEnlaces" id = "cartas">Cartas</button></a>
</div>
