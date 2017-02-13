<?php require_once __DIR__.'/includes/config.php'; ?>
<!DOCTYPE html>
<html>

	<head>
		<title>Vista de registrado</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/plantilla.css')?>"/>
		<link rel="stylesheet" type="text/css" href="<?= $app->resuelve('/css/miCuenta.css')?>"/>
		<script type="text/javascript" src="<?= $app->resuelve('/js/jquery-1.9.1.min.js')?>"></script>
		<script type="text/javascript" src="<?= $app->resuelve('/js/partida.js')?>"></script>
	</head>

	<script>
		var encontrada = false;
		var fin = false;
		window.onload = function() {
			encontrada = false;
		};
		var idInterval;
		var datosPartida;
		function buscar(email) {
				fin = false;
				$("#marcoConfirmar").fadeTo("slow", 1.0);
				$("#opacidad").fadeTo("fast", 0.5);
				$("#opacidad").css("z-index", "1");
				$("#marcoConfirmar").css("z-index", "1");
				$("#opacidad").css("display", "block");
				$("#partida").hide();
				
				idInterval = setInterval(function(){
					if(fin == false && encontrada == false){					
						buscarAjax(email); 
					}}
				, 5000);
		}
		
		function comenzar(){
				console.log(datosPartida);
				var url = "<?= $app->resuelve('/tablero.php')?>";
				window.location.href = url;
		   
		}
		
		function buscarAjax(email){
				console.log("Sigue buscando");
				$.ajax({
					type: "POST",
					url: "buscarPartida.php",
					data: { "email" : email},
					datatype: "json",
					success: function(data){
						datosPartida = JSON.parse(data);
						console.log("Encontrada: " + datosPartida["ok"])
						if(datosPartida["ok"] == true){
							encontrada = true;
							console.log(datosPartida);
							var url = "<?= $app->resuelve('/tablero.php')?>";
							clearInterval(idInterval);
							window.location.href = url;
						}
						else encontrada = false;
					},
					failure: function(data){
						datosPartida = JSON.parse(data);
						console.log("Encontrada: " + datosPartida["ok"])
						console.log(datosPartida["p"]);
					}
				});
		}
		
		function cancelar(email) {
			$("#opacidad").fadeTo("fast", 0.0);
			$("#marcoConfirmar").fadeTo("slow", 0.0);
			$("#opacidad").css("z-index", "-100");
			$("#marcoConfirmar").css("z-index", "-100");
			$("#partida").show();
			$.ajax({
				type: "POST",
				url: "cancelarPartida.php",
				data: {"email": email},
				datatype: "json",
				success: function(data){
					fin = true;
					encontrada = false;
				}
			});
		}
		
	</script>

	<body>
	<?php if($app->usuarioLogueado()){
		$email = $app->emailUsuario();
		?>
		<div id = "opacidad"></div>
		<div id = "capaMadre">
		<?php $app->doInclude('comun/banner.php'); ?>
		<div id = "cuerpo">
			<div id = "botones">
				<?php $app->doInclude('comun/sidebar.php'); ?>
				<div id = "panelDerecho">
					<?php $app->doInclude('comun/enlaces.php'); ?>
					<div id = "descripcion">
						<div id = "marcoConfirmar">
							<div id = "confirmar">
								<p id = "infoBuscador">Buscando Partida</p>
								<div id = "reloj">
										<img class = "cogwheel" id = "cog1" src = "<?= $app->resuelve('/img/cogwheel2.png')?>"></img>
										<img class = "cogwheel" id= "o2" src = "<?= $app->resuelve('/img/o2.png')?>"></img>
										<img class = "cogwheel" id = "cog2" src = "<?= $app->resuelve('/img/cogwheel1.png')?>"></img>
										<img class = "cogwheel" id = "aguja2" src = "<?= $app->resuelve('/img/aguja2.png')?>"></img>
										<img class = "cogwheel" id = "aguja" src = "<?= $app->resuelve('/img/aguja.png')?>"></img>
								</div>
								<button onclick="comenzar()" id = "comenzar">Comenzar</button>
								<button onclick="cancelar('<?=$email?>')" id = "cancelar">Cancelar</button>
							</div>
						</div>
						<div id= "bloqueFormulario">
						<?php if($app->rolUsuario() == "Usuario"){?>

							<button onclick="buscar('<?=$email?>')" class = "partida" id = "partida">Jugar partida</button>

						<?php	} else{  ?>

							<a href ="<?= $app->resuelve('/gestionUsuarios.php')?>"><button class = "gestionAdmin" id = "gestionUser">Gestión de usuarios</button></a>
							<a href ="<?= $app->resuelve('/gestionCartas.php')?>"><button class = "gestionAdmin" id = "gestionCartas">Gestión de cartas</button></a>

						<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php }
		else
			header('Location: '.$app->resuelve('/index.php'));
	?>
	</div>
	</body>
</html>
