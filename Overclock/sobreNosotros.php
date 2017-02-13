<?php require_once __DIR__.'/includes/config.php'; ?>
<!DOCTYPE html>
<html>

	<head>
		<title>Acerca de</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css') ?>" />
	</head>
	
	<body> <!-- Todo este código común a todas las vistas acabará formando parte de una cabecera que se importará desde todas las vistas -->
		<div id = "capaMadre">
		<?php $app->doInclude('comun/banner.php'); ?>
		<div id = "cuerpo">
			<div id = "botones"> <!-- Al meter los botones dentro de los links, no hace falta pinchar en el texto, funciona como un botón -->
				<?php $app->doInclude('comun/sidebar.php'); ?>
				<div id = "panelDerecho">
					<?php $app->doInclude('comun/enlaces.php'); ?>
					<h2>El equipo de desarrollo de Overclock está formado por:</h2>
		<div id = "descripcion">
			<ul>
			<li><a href = "#adri">Adrián Muñoz Gámez</a></li>
			<li><a href = "#adnres">Andrés Pascual Contreras</a></li>
			<li><a href = "#henry">Enrique Ituarte Martínez-Millán</a></li>
			<li><a href = "#jose">Jose Javier Escudero Gómez</a></li>
			<li><a href = "#sergio">Sergio Freire Fernández</a></li>
			<li><a href = "#serx">Sergio Ulloa López</a></li>
			</ul>
			<a name = "adri">
				<div class = "info">
					<img class = "imagenMiembro" src ="img/adrian.png"></img>
					<div class = "infoMiembro">
					<ul>
						<li> Nombre: Adrián Muñoz Gámez </li>
						<li> Correo: adrimu02@ucm.es </li>
						<li> Aficiones: Me encanta leer manga y ver anime, jugar a MMO's y salir al cine con mis amigos. </li>
						
					</ul>
					</div>
				</div>
			</a>
			<a name = "andres">
				<div class = "info">
					<img class = "imagenMiembro" src ="img/andres.png"></img>
					<div class = "infoMiembro">
					<ul>
						<li> Nombre: Andrés Pascual Contreras </li>
						<li> Correo: a.pascual@ucm.es </li>
						<li> Aficiones: Me gusta mucho leer y jugar a videojuegos con mis amigos en Skype, ver series y películas.</li>
						
					</ul>
					</div>
				</div>
			</a>
			<a name = "henry">
				<div class = "info">
					<img class = "imagenMiembro" src ="img/henry.png"></img>
					<div class = "infoMiembro">
					<ul>
						<li> Nombre: Enrique Ituarte Martínez-Millán </li>
						<li> Correo: eituarte@ucm.es </li>
						<li> Aficiones: Suelo practicar Calistenia, ir al cine, salir con mis amigos, programar. </li>
						
					</ul>
					</div>
				</div>
			</a>
			<a name = "jose">
				<div class = "info">
					<img class = "imagenMiembro" src ="img/josejavier.png"></img>
					<div class = "infoMiembro">
					<ul>
						<li> Nombre: José Javier Escudero Gómez </li>
						<li> Correo: josejesc@ucm.es </li>
						<li> Aficiones: Cinéfilo, amante de la música, de los videojuegos y la F1.  </li>
						
					</ul>
					</div>
				</div>
			</a>
			<a name = "sergio">
				<div class = "info">
					<img class = "imagenMiembro" src ="img/sergio.png"></img>
					<div class = "infoMiembro">
					<ul>
						<li> Nombre: Sergio Freire Fernández</li>
						<li> Correo: sefreire@ucm.es </li>
						<li> Aficiones: Jugar a videojuegos, baloncesto, comer, ver las motos y el anime.</li>
						
					</ul>
					</div>
				</div>
			</a>
			<a name = "serx">
				<div class = "info">
					<img class = "imagenMiembro" src ="img/serx.png"></img>
					<div class = "infoMiembro">
					<ul>
						<li> Nombre: Sergio Ulloa López </li>
						<li> Correo: serulloa@ucm.es </li>
						<li> Aficiones: Me encanta leer manga y ver ánime, jugar a MMO's, salir al cine con mis amigos, ir de fiesta y liarla parda. </li>
						
					</ul>
					</div>
				</div>
			</a>
		</div>
		
				</div>
			</div>
		</div>
		</div>
	</body>
</html>