<?php
	use xampp\htdocs\Overclock;
	$app = Overclock\Aplicacion::getSingleton();
?>
<div id = "banner">	
		<img id = "verclock" src = "<?= $app->resuelve('/img/fondoLogo.png')?>"></img>
		<div class = "portal">		
			<img class = "portal" id = "portal2" src = "<?= $app->resuelve('/img/portal3.png')?>"></img>	
			<img class = "portal" id = "portal3" src = "<?= $app->resuelve('/img/portal4.png')?>"></img>	
			<img class = "portal" id = "portal4" src = "<?= $app->resuelve('/img/portal5.png')?>"></img>	
		</div>
		<img id = "verclock" src = "<?= $app->resuelve('/img/overclock.png')?>"></img>		
</div>