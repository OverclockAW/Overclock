<?php 
	require_once __DIR__.'/includes/config.php'; 
	use xampp\htdocs\Overclock\Partida;
?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>Partida</title>
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css')?>"/>
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/tablero.css')?>"/>
		<script type="text/javascript" src="<?= $app->resuelve('/js/jquery-1.9.1.min.js')?>"></script>
		<script type="text/javascript" src="<?= $app->resuelve('/js/partida.js')?>"></script>
		<script type="text/javascript" src="<?= $app->resuelve('/js/jquery-ui.js')?>"></script>
	</head>
	<body>
		<?php
				$i = 0;
				$c = 0;
				$s = 0;
				if(isset($_SESSION["partida"])){
					$id = $_SESSION["partida"];
					$partida = new Partida($id);
					$finalizada = $partida->getFinalizada();
					if($finalizada == "Si"){
						$url = $app->resuelve('/ranking.php');
						header("Location: ".$url);
					}
					$turnoActual = $partida->getTurno();
					$finalizada = $partida->getFinalizada();
					$miRol;
					$contador;
					$miEmail;
					$suEmail;
					if($_SESSION['email'] == $partida->getJ1()){
						$miRol = "Aventurero";
						$suRol = "Master";
						$miEmail = $partida->getJ1();
						$suEmail = $partida->getJ2();
						$contador = $partida->getBossesRestantes();
					}
					
					else{
						$contador = $partida->getWipes();
						$miRol = "Master";
						$suRol = "Aventurero";
						$suEmail = $partida->getJ1();
						$miEmail = $partida->getJ2();
					}
					
					$registro = $partida->getRegistro();
					$miMano = $partida->getMano($miRol);
					$misCartasMano = $miMano->getCartas();
					$miCampo = $partida->getCampo($miRol);
					$misCartasCampo = $miCampo->getCartas();
					
					$suMano = $partida->getMano($suRol);
					$susCartasMano = $suMano->getCartas();
					$suCampo = $partida->getCampo($suRol);
					$susCartasCampo = $suCampo->getCartas();
					
					$params = array();
					array_push($params, $miRol);
					$p = json_encode($params, JSON_UNESCAPED_UNICODE);
					
		?>
		<div id = "borde" onmouseenter = 'comprobarTurno("<?= $miRol?>", "<?=$turnoActual?>", <?=$p?>)'>
			<div id = "deseleccionar" onclick = "desseleccionar()"></div>
			<div id = "contadorWipes" class = "opcionesPartida"><p id = "wipes"><?=$contador?></p></div>
			<div id = "marcoJefe">
				<div id = "cartaJefe">
					<?php
					foreach($susCartasCampo as $cartaCM) {
						$tipo = $cartaCM->getTipo();
						$miClaseP = "negra";
						$marcoCarta = $cartaCM->getMarco();
						$energia = "";
						if($tipo == "Boss" || $tipo == "Evento"){
							$miClaseP = "blanca";
						}
						if($tipo == "Boss"){
							$energia = $cartaCM->getEnergia();
						}
						$paramsC = $cartaCM->fromCartaToArray();
						array_push($paramsC, $s); // La posición de la carta en el array interno
						$targeteable = true;
						array_push($paramsC, $targeteable); // targeteable
						$miCampo = false; // si está en mi campo
						array_push($paramsC, $miCampo);
						$rolCarta = $suRol;
						array_push($paramsC, $suRol);
						array_push($paramsC, $turnoActual);
						$carta = json_encode($paramsC, JSON_UNESCAPED_UNICODE);
						?>
						<div class = "cartaOtro" onmouseover = "unspread('<?= sizeof($misCartasMano)?>')" id = "campoj2_<?=$s?>" onclick = 'apareceCarta("<?=$miClaseP?>", <?=$carta?>, "<?=$app->resuelve('/img/cartas/'.$cartaCM->getImagen().'.png')?>", "<?=$marcoCarta?>")'
							ondblclick = "seleccionarTarget('<?=$s?>', '<?=$miCampo?>', '<?=$rolCarta?>', '<?=$targeteable?>')">
							<img class = "personajeAventurero" src = "<?=$app->resuelve('/img/cartas/'.$cartaCM->getImagen().'.png')?>"></img>
							<img class = "marcoAventurero" src = "<?=$marcoCarta?>"></img>
							<p class = "<?=$miClaseP?>" id = "nombreCarta"> <?=$cartaCM->getNombre()?></p>
							<p class = "<?=$miClaseP?>" id = "hp"> <?=$cartaCM->getHp()?></p>
							<p class = "<?=$miClaseP?>" id = "energia"> <?=$energia?></p>
						</div>
					<?php
						$s++;
					}
				?>
				</div>
			</div>
			<button id = "rendirse" class = "opcionesPartida" onclick = "rendirse('<?=$turnoActual?>', '<?=$miRol?>')">Rendirse</button>
			<div id = "cartasAventurero">
				<div id = "cartas">
				<?php
				foreach($misCartasCampo as $cartaC) {
						$tipo = $cartaC->getTipo();
						$miClaseP = "negra";
						$marcoCarta = $cartaC->getMarco();
						$energia = "";
						if($tipo == "Boss" || $tipo == "Evento"){
							$miClaseP = "blanca";
						}
						if($tipo == "Boss"){
							$energia = $cartaC->getEnergia();
						}
						$paramsC = $cartaC->fromCartaToArray();
						array_push($paramsC, $c);
						$targeteable = false;
						array_push($paramsC, $targeteable); // targeteable
						$miCampo = true; // si está en mi campo
						array_push($paramsC, $miCampo);
						$rolCarta = $miRol;
						array_push($paramsC, $miRol);
						array_push($paramsC, $turnoActual);
						$carta = json_encode($paramsC, JSON_UNESCAPED_UNICODE);
					?>
					<div class = "cartaAventurero" onmouseover = "unspread('<?= sizeof($misCartasMano)?>')" id = "campoj1_<?=$c?>" onclick = 'apareceCarta("<?=$miClaseP?>", <?=$carta?>, "<?=$app->resuelve('/img/cartas/'.$cartaC->getImagen().'.png')?>", "<?=$marcoCarta?>")'
						ondblclick = "seleccionarTarget('<?=$c?>', '<?=$miCampo?>', '<?=$rolCarta?>', '<?=$targeteable?>')">
						<img class = "personajeAventurero" src = "<?=$app->resuelve('/img/cartas/'.$cartaC->getImagen().'.png')?>"></img>
						<img class = "marcoAventurero" src = "<?=$marcoCarta?>"></img>
						<p class = "<?=$miClaseP?>" id = "nombreCarta"> <?=$cartaC->getNombre()?></p>
						<p class = "<?=$miClaseP?>" id = "hp"> <?=$cartaC->getHp()?></p>
						<p class = "<?=$miClaseP?>" id = "energia"> <?=$energia?></p>
					</div>
				<?php
					$c++;
				}
			?>
				</div>
			</div>
			<div id = "marcoMano" onmouseover = "spread('<?= sizeof($misCartasMano)?>')">
				<div id = "mano" >
				<div id = "bordeMano"></div>
				<div id = "cartasMano">
				<?php
				foreach($misCartasMano as $carta) {
					$tipo = $carta->getTipo();
					$miClaseP = "negra";
					$marcoCarta = $carta->getMarco();
					$energia = 0;
					if($tipo == "Boss" || $tipo == "Evento"){
						$miClaseP = "blanca";
						
					}
					if($tipo == "Boss"){
						$energia = $carta->getEnergia();
					}
					$params = array();
					array_push($params, $miRol);
					array_push($params, $i);
					$p = json_encode($params, JSON_UNESCAPED_UNICODE); // Pasamos el array a json para poderlo enviar por parámetros	
					$paramsC = $carta->fromCartaToArray();
					array_push($paramsC, $i);
					array_push($paramsC, false); // targeteable
					$miCampo = false; // si está en mi campo
					array_push($paramsC, $miCampo);
					array_push($paramsC, $miRol);
					array_push($paramsC, $turnoActual);
					$c = json_encode($paramsC, JSON_UNESCAPED_UNICODE);
					?>
					<div class = "cartaMazo">
					<div class = "cartaAventureroMano" ondblclick = 'accion("<?=$turnoActual?>", "<?= $miRol?>","sacarCarta", <?=$p?>)' id = "mano_<?=$i?>" 
					onclick = 'apareceCarta("<?=$miClaseP?>", <?=$c?>, "<?=$app->resuelve('/img/cartas/'.$carta->getImagen().'.png')?>", "<?=$marcoCarta?>")'>
						<img class = "personajeAventurero" src = "<?=$app->resuelve('/img/cartas/'.$carta->getImagen().'.png')?>"></img>
						<img class = "marcoAventurero" src = "<?=$marcoCarta?>"></img>
						<p class = "<?=$miClaseP?>" id = "nombreCarta"> <?=$carta->getNombre()?></p>
						<p class = "<?=$miClaseP?>" id = "hp"> <?=$carta->getHp()?></p>
						<p class = "<?=$miClaseP?>" id = "energia"> <?=$energia?></p>
						
					</div>
					</div>
				<?php
					$i++;
				}
				
			?>
				</div>
				</div>
				<div id = "marcoError">
					<p id = "errorPartida"></p>
				</div>
			</div>
			<div id = "infoCarta">
				<div class = "infoCartaAventurero">
					<img class = "infoPersonajeAventurero"></img>
					<img class = "infoMarcoAventurero"></img>
					<p id = "infoNombreCarta">Nombre de la carta</p>
					<p id = "infoHp"></p>
					<p id = "infoDescripcion"></p>
					<p id = "infoEnergia"></p>
					<div id = "infoHabilidades">
					</div>
				</div>
			</div>
			<div id = "infoHabilidad">
				<h4 id = "infoNombreHabilidad">Nombre</h4>
				<P id = "infoTipoHabilidad"></p>
				<P id = "infoEnergiaHabilidad"></p>
				<P id = "infoValorHabilidad"></p>
				<P id = "infoCosteHabilidad"></p>
				<P id = "infoAreaHabilidad"></p>
			</div>
			<a ><button class = "opcionesPartida" id = "ayuda" onclick = "mostrarAyuda()">Ayuda</button></a>
			<a ><button class = "opcionesPartida" id = "pasarTurno" onclick = "confirmarTurno('<?=$turnoActual?>', '<?=$miRol?>')">Terminar turno</button></a>
			<ol id = "log">
				<?php 
				foreach($registro as $reg) {?>
					<li> <?=$reg?> </li>
				<?php 
				}
				?>
			</ol>
			<div id = "opacidad"></div>
			<div class = "marcoConfirmar" id = "marcoConfirmar1">
				<div class = "confirmar" id = "confirmar1">
					<p>¿Seguro que has terminado tu turno?</p>
					<?php 
					$parametros = array();
					$p = json_encode($parametros, JSON_UNESCAPED_UNICODE); // Pasamos el array a json para poderlo enviar por parámetros
					?>
					<button id = "aceptar" class = "botonesConfirmacion" onclick = 'terminarTurno("<?= $miRol?>", "<?=$turnoActual?>", "<?=$p?>")'> Aceptar</button>
					<button id = "cancelar" class = "botonesConfirmacion" onclick = "cancelar()"> Cancelar</button>
				</div>
			</div>
			<div class = "marcoConfirmar" id = "marcoConfirmar2">
				<div class = "confirmar" id = "confirmar2">
					<p id = "textoDialogo">¡Te toca! </p>
					<P id = "textoDialogo"> Pulsa aceptar para comenzar tu turno.</p>
					<?php 
					$parametros = array();
					$p = json_encode($parametros, JSON_UNESCAPED_UNICODE); // Pasamos el array a json para poderlo enviar por parámetros
					?>
					<button id = "aceptar2" class = "botonesConfirmacion" onclick = 'empezarTurno()'> Aceptar</button>
				</div>
			</div>
			<div class = "marcoConfirmar" id = "marcoConfirmar3">
				<div class = "confirmar" id = "confirmar3">
					<p id = "textoDialogo">¿Seguro? </p>
					<P id = "textoDialogo"> Si pulsas aceptar, ganará tu oponente.</p>
					<button id = "aceptar3" class = "botonesConfirmacion" onclick = "rendirseAjax('<?=$turnoActual?>', '<?=$miRol?>', '<?=$id?>', '<?=$miEmail?>')"> Aceptar</button>
					<button id = "cancelar" class = "botonesConfirmacion" onclick = "cancelar()"> Cancelar</button>
				</div>
			</div>
			<div class = "marcoConfirmar" id = "marcoConfirmar4">
				<div class = "confirmar" id = "confirmar4">
					<p id = "perdedor">
					<p id = "ganador">
					<p id = "redireccion">En unos segundos serás redireccionado al Ranking</p>
				</div>
			</div>
			<div class = "marcoConfirmar" id = "marcoConfirmar5">
			    <div class = "confirmar" id = "confirmar5">
			    <h2> AYUDA DE LA INTERFAZ </h2>
					<h3> Tablero </h3>
					<p>
						<ul>
							<li>Cuando termines las acciones que deseas realizar debes pulsar el botón “Terminar turno” (en la esquina inferior izquierda), para que tu rival pueda jugar.</li>
							<li>Si deseas rendirte pulsa el botón “Rendirse” (en la esquina superior derecha)</li>
							<li>El número de la esquina superior izquierda indica el número de wipes que el Master le ha provocado al Aventurero (vista del Master) o el número de Jefes que le quedan por derrotar al Aventurero (vista del Aventurero)</li>
							<li>En el extremo derecho, a media altura, se encuentra el Log de Partida, donde podrás encontrar información de todas las acciones que se han llevado a cabo</li>
						</ul>
					
					<h3> Campo </h3>
					<p>
						<ul>
							<li>En la zona media del tablero se encuentra el “campo”, donde ambos jugadores podéis desplegar vuestras cartas</li>
							<li>Como máximo pueden colocarse 5 cartas por jugador en el campo</li>
							<li>Solo puedes usar habilidades con la cartas que tengas desplegadas en tu campo</li>
							<li>Solo puedes tomar como objetivo las cartas que tu o tu rival tengáis en el campo</li>
						</ul>
					
					<h3> Cartas </h3>
					<p>
						<ul>
							<li>Para ver mejor las cartas de tu mano, pasa el ratón por encima de ellas</li>
							<li>Para desplegar una carta de tu mano haz doble click sobre ella</li>
							<li>Si deseas consultar detalles sobre las habilidades de las cartas, pasa el ratón por encima del botón de la habilidad y saldrá un desplegable informativo</li>
							<li>Para utilizar una habilidad, haz doble click primero sobre la carta objetivo y después sobre el botón de la habilidad (Recuerda: habilidades de ataque solo sobre cartas enemigas y habilidades de sanación sobre las tuyas)</li>
						</ul>
			    <button id = "entendido" class = "botonesConfirmacion" onclick = 'cerrarAyuda()'> Entendido</button>
			    </div> 
   			</div>			
		</div>
		<?php }
		else{
			?>
			<div class = "marcoConfirmar" id = "marcoConfirmar6">
				<div class = "mensaje" id = "mensajePartidaFinalizada">
				<p id = "redireccion">Esta partida ya ha terminado.</p>
				<a href = <?=$app->resuelve('/ranking.php')?>>Volver al ranking</a>
				</div>
			</div>
			<?php
		}?>
	</body>

</html>
