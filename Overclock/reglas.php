<?php require_once __DIR__.'/includes/config.php';?>
<!DOCTYPE html>
<html>

	<head>
		<title>Reglas</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css') ?>">
	</head>
	
	<body> <!-- Todo este cÃ³digo comÃºn a todas las vistas acabarÃ¡ formando parte de una cabecera que se importarÃ¡ desde todas las vistas -->
		<div id = "capaMadre">
		<?php $app->doInclude('comun/banner.php');?>
		
		<div id = "cuerpo">
			<div id = "botones"> <!-- Al meter los botones dentro de los links, no hace falta pinchar en el texto, funciona como un botÃ³n -->
				<?php $app->doInclude('comun/sidebar.php');?>
				
				<div id = "panelDerecho">
					<?php $app->doInclude('comun/enlaces.php');?>
					<h2> Mecánicas básicas del juego </h2>
					<div id = "descripcion">
						<p>El sistema de juego parte de un desarrollo simple y sencillo pero que a la vez permite la ejecución
							de partidas complejas dependiendo de la estrategia que sigan los jugadores. A continuación
							vamos a tratar brevemente los roles de ambos jugadores y el sistema de juego. </p>
						<ul class = "lista_detalles">
							<li> <em>Aventurero: </em> nuestra misión será derrotar a todos los jefes de la mazmorra. El mazo de
										cartas de este jugador posee dos tipos de naipe, los personajes (que representan los
										miembros del grupo) y los objetos (equipamiento de todo tipo tales como armas,
										armaduras, herramientas, consumibles, etc). 
										</li>
							<li> <em>Dungeon Master: </em> nuestro objetivo consistirá en derrotar (hacer "wipe") al grupo del
										aventurero un número concreto de veces para provocar su retirada. El mazo de este
										jugador tiene también dos tipos de cartas, los jefes de la mazmorra y eventos que
										representan el manejo de la mazmorra por parte del master provocando efectos
										adversos al aventurero. </li>
						</ul>
							
						<p>El desarrollo de la partida consiste en batallas entre los personajes del aventurero y los jefes del
							master, aderezadas con el uso de los otros dos tipos de cartas (objetos y eventos
							respectivamente). El mazo del aventurero se recicla completamente, por lo que los personajes
							caídos pueden volver a ser utilizados cuando regresen a su mano, sin embargo los jefes del
							master mueren definitivamente, pudiendo únicamente recuperar las cartas de eventos.
							Partiendo de este simple desarrollo, cada partida permite aplicar múltiples estrategias según las
							situaciones que se planteen. </p>
						<ul class = "lista_detalles">
							<li>Una partida o "dungeon" se compone por un aventurero y un master. Ambos son usuarios registrados con los mismos privilegios en la página.</li>
							<li>El mazo del aventurero se compone por personajes y objetos.
								Todas las cartas que sean derrotadas del mazo se irán reciclando para que puedan volver a la mano de este para ser reutilizadas.</li>
							<li>El mazo del master es un mazo predefinido por el juego y está formado por eventos de mazmorra, similar en funcionamiento a las magias del aventurero,
								 y jefes. Un boss es una criatura mas poderosa que las cartas de personaje del aventurero y que además tienen una serie de habilidades disponibles en el combate durante su turno.
								 El master empieza con todas las cartas de boss en la mano y además irá robando de forma aleatoria cartas de evento.
								Solo las cartas de evento de mazmorra se reciclan tras su uso para su reutilización.</li>
							<li>Segán juegues como master o como aventurero el objetivo de la partida es distinto. 
								Para que un aventurero gane deberá eliminar todas las cartas de jefe del adversario para dar por concluida la mazmorra. 
								Para que un master gane deberá limpiar la mesa del adversario un número de veces concretas, 
								lo que supondrá que la incursión del aventurero en la mazmorra ha fracasado y ha tenido que retroceder. </li>
							<li>En una partida el master solo podrá tener una carta de jefe activa en el tablero al mismo tiempo 
								junto a todas las cartas evento que crea oportunas hasta llenar su hueco disponible de cartas sobre la mesa. 
								Durante el turno del master solo podrá usar o la carta de jefe para atacar con alguna habilidad o alguna de las cartas de evento de mazmorra, 
								pero nunca podrá usar ambos tipos de cartas durante el transcurso de un turno. 
								Durante un turno podrá poner todas las cartas de evento sobre la mesa que el desee y tenga en su mano hasta llenar los huecos disponibles para ello. </li>
							<li> En una partida el aventurero podrá colocar tantas cartas de personaje como espacios tenga disponibles en su mesa 
								o magias para atacar al enemigo o potenciar a las cartas de personaje puestas sobre la mesa. 
								Las cartas de personaje tendrán diferentes roles para realizar funciones concretas. 
								Al contrario de las cartas de personaje que duran en la mesa hasta que son derrotadas por el master, 
								las cartas de magia se consumen al usarse y desaparecen del tablero para ser recicladas. </li>
							<li>Todas las cartas de criatura tienen una o varias habilidades disponibles a su disposición. 
								Las del aventurero solo disponen de una para usar cada turno y los jefes pueden usar tantas como puntos de habilidad disponible. 
								Las habilidades del jefe tienen un coste de energía concreto.</li>
							<li>Los jefes son las criaturas disponibles por el master. Poseen mayor vida que las cartas de criatura del aventurero y varias habilidades disponibles para su uso.  
								Además, disponen de un atributo extra llamado energía que regula el número de habilidades por turno.</li>
							<li>Los tanques son los encargados de recibir el daño de los jefes, si no son en área. Tienen una habilidad para curarse a la vez que hacen daño.</li>
							<li>Los atacantes son los encargados de generar daño a las cartas de jefe. 
								Dejarlos sin la protección de un guerrero que absorba los ataques supone perder la carta debido a su poca vida.</li>
							<li>Los sanadores son los encargados de restaurar parte de los puntos de vitalidad de personajes heridos. 
								No pueden atacar y poseen pocos puntos de vitalidad.</li>
							<li>Los especialistas son personajes que no se centran en el combate sino que aportan habilidades especiales útiles 
								(detectar trampas, potenciar otros personaje, etc.)</li>
							<li>El jugador no puede usar dos cartas de evento u objeto iguales en el mismo turno.</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		</div>
	</body>
	
</html>