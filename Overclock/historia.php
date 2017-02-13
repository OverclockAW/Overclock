<?php require_once __DIR__.'/includes/config.php'; ?>
<!DOCTYPE html>
<html>

	<head>
		<title>Historia</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css') ?>" />
	</head>
	<body> 
		<div id = "capaMadre">
		<?php $app->doInclude('comun/banner.php'); ?>
		<div id = "cuerpo">
			<div id = "botones">
				<?php $app->doInclude('comun/sidebar.php'); ?>
				<div id = "panelDerecho">
					<?php $app->doInclude('comun/enlaces.php'); ?>
					<h1>Historia</h1>
					<div id = "descripcion">
						<p>Se dice que Rusty Pit fue una vez una ciudad gloriosa y poderosa, 
						que forjó grandes leyendas, sin embargo ahora no es más que óxido,  
						polvo y aceite de motor, una sombra de lo que un día fue.
						</p>

						<p>El doctor Venancious Pemberley (alias Doc. Venley), 
						un eminente científico y honorable ciudadano de Rusty Pit,
						estaba obsesionado en encontrar una forma de devolver 
						la grandeza que antaño había poseído su amada ciudad. 
						Para ello se sumergió en diversos experimentos y proyectos 
						tecnológicos que pudiesen atraer visitantes y comercio a su ciudad. 
						</p>

						<p>Probó con todo tipo de cosas, pero no dieron los resultados esperados,
						 hasta que se topó con una idea que revolucionaría el mundo,
						 el teletransporte. Con ese objetivo en mente se puso manos a la obra 
						 y comenzó a trabajar.
						</p>

						<p>Durante un par de años fue dejando todas sus demás ocupaciones de lado
						 y centrándose solo en el portal espacial que estaba creando, 
						 y tras varios años de mucho esfuerzo y pocas horas de sueño lo consiguió.
						 Dos grandes portales los cuáles a través de cada uno de ellos podía viajar
						 instantáneamente al otro.
						</p>

						<p>Orgulloso de su hazaña reunió a todo el pueblo para hacer una demostración
						 de cómo funcionaba su creación, sin embargo un día de tormenta 
						 no era el momento idóneo para realizarla. En plena demostración 
						 un trueno sacudió el laboratorio y provocó una sobrecarga en el 
						 sistema electrónico del portal, transformando lo que en principio 
						 era un portal espacial en un portal dimensional. Y cuando el ayudante
						 del Doc. Venley se dispuso a entrar en el portal lo que salió por el otro 
						 lado no fue el ayudante sino una bestia que pocos de los presentes
						 se atrevieron a describir. Consciente del peligro Venley cortó 
						 la electricidad apenas unos segundos antes de que la bestia atravesase
						 totalmente el portal.
						</p>

						<p>Venley lo consideró un fracaso y decidió dejar el portal apartado en su garaje,
						 pero la noticia de lo sucedido se extendió como la pólvora por las 
						 ciudades cercanas y comenzaron a llegar multitud de aventureros 
						 y comerciantes interesados en el portal. Les atraía la idea de explorar 
						 otras dimensiones y descubrir sus peligros y sus riquezas. 
						 La idea no entusiasmaba a Venley pero vió una oportunidad de devolver 
						 la gloria de antaño a su ciudad.
						</P>

						<p>Después de una puesta a punto, Venley reactivó el portal 
							y grupos de aventureros comenzaron a viajar entre dimensiones 
							descubriendo sus secretos o saqueando sus riquezas.
						</p>

						<p>Hoy en día Rusty Pit vuelve a ser una gran ciudad llena de vida 
						aunque conserva ese olor a óxido y motor que la caracteriza. 
						Así que ahora te toca decidir a ti Aventurero, 
						¿te quedarás sentado esperando a que tu espada se oxide o te unirás a la búsqueda? 
						</p>

					</div>
				</div>
			</div>
		</div>
		</div>
	</body>
	
</html>