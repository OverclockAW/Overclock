	$spread = false;
	var nCartaMano = 0;
	var comprobar = false;
	var idInterval; // Id del itervalo de comprobar turno. Para poder liberarlo
	var targetEnemigo = -1; 
	var targetAmigo = -1;
	var rolTarget;
	var posicion = -1;
	var x = 0; // Desplazamiento al colocar las cartas en el tablero
	var contador;
	
	$(document).keyup(function(e) {
	     if (e.keyCode == 27) { // escape key maps to keycode `27`
	    	 $("#infoCarta").fadeTo("slow", 0.0);
	 		$("#infoCarta").css("z-index", "-10");
	 		$("#infoHabilidad").fadeTo("slow", 0.0);
	 		$(".cartaOtro").css("box-shadow", "none");
	 		$(".cartaAventurero").css("box-shadow", "none");
	 		targetEnemigo = -1; // Quitamos el target
	 		targetAmigo = -1;
	 		posicion = -1;
	    }
	});
	
	// Comprueba si te toca jugar. Función periódica que se llama a turnos cuando no es tu turno
	function comprobarTurno(miRol, turnoActual, p){
		if(comprobar == false && miRol != turnoActual){
		idInterval = setInterval(function(){ // setInterval devuelve un valor
			//if(miRol != turnoActual){					
				turnos(turnoActual, miRol,"comprobarMiTurno", p);
				var a = [];
				turnos(turnoActual, miRol, "getMovimientos", a);
				comprobar = true;
			//}
			}
		, 3000);
		}
	}
	
	// Desplaza el scroll del log de habilidades hacia abajo
	window.onload = function(){
		$("#log").animate({ scrollTop: $(document).height() }, "slow");
	};
	
//	function sacar(event, turnoActual, miRol, nombreFuncion, p){
//	 $('.cartaAventurero').bind('keydown', function(event, turnoActual, miRol, nombreFuncion, p) {
//		    console.log(event.keyCode);
//		    switch(event.keyCode){
//		       //....your actions for the keys .....
//		    }
//		 });
//	}
//	function sacar(event, turnoActual, miRol, nombreFuncion, p){
//		var x = event.which || event.keyCode;
//		console.log("Tecla pulsada:" + x);
//		//accion(turnoActual, miRol, nombreFuncion, p);
//	}
	function seleccionarTarget(miPosicion, estaEnMiCampo, rolCarta, targeteable){
		if(targeteable == true){
			targetEnemigo = miPosicion;
			$(".cartaOtro").css("box-shadow", "none");
			$("#campoj2_" + miPosicion).css("box-shadow", "0px 0px 30px red");
		}
		else if (targeteable == false && estaEnMiCampo == true){
			targetAmigo = miPosicion;
			$(".cartaAventurero").css("box-shadow", "none");
			$("#campoj1_" + miPosicion).css("box-shadow", "0px 0px 30px #009688");
		}
		console.log("DobleClick mi posicion: " + miPosicion);
		console.log("DobleClick targetAmigo: " + targetAmigo);
		console.log("DobleClick rolTarget: " + rolCarta);
		console.log("DobleClick targetEnemigo: " + targetEnemigo);
		console.log("---------------");
		rolTarget = rolCarta;
		posicion = miPosicion;
	}
	
	function apareceCarta(claseP, carta, imagen,  marco){ // muestra la carta seleccionada en grande para poder usarla
		$("#infoCarta").hide();
		$("#infoCarta").css("z-index", "-10");
		$("#infoCarta").css("z-index", "1");
		$("#infoCarta").fadeTo("slow", 1.0);
		$("#infoNombreCarta").text(carta[0]);
		$("#infoNombreCarta").attr("class", claseP);
		$("#infoHp").attr("class", claseP);
		$(".infoPersonajeAventurero").attr("src", imagen);
		$("#infoDescripcion").text(carta[1]);
		$("#infoDescripcion").attr("class", claseP);
		$("#infoHp").text(carta[2]);
		if(carta[4] == "Boss"){ // Ponemos la energía en función de si es o no un boss
			$("#infoEnergia").text(carta[3]);
		}
		else{
			$("#infoEnergia").text(carta[3]);
			$("#infoEnergia").hide();
			
		}
		if(carta[4] == "Boss" || carta[4] == "Personaje"){ // Si el objeto tiene vida, la mostramos
			$("#infoHp").text(carta[2]);
		}
		else{
			$("#infoHp").text(carta[2]);
			$("#infoHp").hide();
		}
		var i = carta[6];
		var encontrada = false;
		var j = 0;
		$(".cartaAventurero").each(function(){
			if($(this).attr("id") == ("campoj1_" + i)){
				posicion = i;
				encontrada = true;
			}
			j++;
		});
		
		if(encontrada == false){
			posicion = j-1;
		}
		$(".infoMarcoAventurero").attr("src", marco);
		var habilidades = carta[5];
		var div = document.getElementById('infoHabilidades');
		while(div.firstChild){
		    div.removeChild(div.firstChild);
		}
		for (i = 0; i < habilidades.length; i++){
			nuevaEntrada = document.createElement('button');
			nuevaEntrada.setAttribute("class", "botonesHabilidades");
			//nuevaEntrada.setAttribute("disabled", true);
			nuevaEntrada.innerHTML = habilidades[i].nombre;
			nuevaEntrada.setAttribute("onmouseover", "apareceHabilidad('" + habilidades[i].nombre + "', '" + habilidades[i].tipo + "', '" + habilidades[i].valor + "', '" + habilidades[i].coste + "', '" + habilidades[i].area + "')");
			nuevaEntrada.setAttribute("onmouseout", "desapareceHabilidad()");
			
			if($(".botonesHabilidades").is("activo")){
				$(".botonesHabilidades").prop("disabled", false);
			}
			var h = habilidades[i];
			// Hay que coger el número asociado al id
			var params = [posicion, h, parseInt(targetAmigo), rolTarget, parseInt(targetEnemigo), carta[8]];			
			var pars = JSON.stringify(params);
			nuevaEntrada.setAttribute("onclick", "accion('"+carta[10]+"', '"+carta[9]+"', 'usarHabilidad', '"+pars+"')"); // 10 -> turnoActual, 9 -> miRol
			document.getElementById('infoHabilidades').appendChild(nuevaEntrada);
		}

		console.log("Click mi posicion: " + posicion);
		console.log("Click targetAmigo: " + targetAmigo);
		console.log("Click rolTarget: " + rolTarget);
		console.log("Click targetEnemigo: " + targetEnemigo);
		console.log("---------------");
		
	}
	
	function apareceHabilidad(nombre, tipo, valor, coste, area){
		$("#infoHabilidad").fadeTo("fast", 1.0);
		$("#infoNombreHabilidad").text(nombre);
		$("#infoTipoHabilidad").text("Tipo: " + tipo);
		$("#infoValorHabilidad").text("Valor: " + valor);
		$("#infoCosteHabilidad").text("Coste: " + coste);
		$("#infoAreaHabilidad").text("Área: " + area);
	}

	function desapareceHabilidad(){
		$("#infoHabilidad").css("display", "none");
	}
	function desseleccionar(){
		$("#infoCarta").fadeTo("slow", 0.0);
		$("#infoCarta").css("z-index", "-10");
		$("#infoHabilidad").fadeTo("slow", 0.0);
		$(".cartaOtro").css("box-shadow", "none");
		$(".cartaAventurero").css("box-shadow", "none");
		targetEnemigo = -1; // Quitamos el target
		targetAmigo = -1;
		posicion = -1;
	}
	
	function rendirse(turnoActual, miRol){
		if(turnoActual == miRol){
			$("#marcoConfirmar3").fadeTo("slow", 1.0);
			$("#marcoConfirmar3").css("z-index", "1");
			$("#opacidad").fadeTo("fast", 0.5);
			$("#opacidad").css("z-index", "0");
			$("#opacidad").css("display", "block");
		}
	}
	function rendirseAjax(turnoActual, miRol, id, miEmail) {
		$("#opacidad").fadeTo("fast", 0.0);
		$("#marcoConfirmar3").fadeTo("slow", 0.0);
		$("#opacidad").css("z-index", "-100");
		$("#marcoConfirmar3").css("z-index", "-100");
		// Falta llamar a accion
		var params = [id, miEmail];
		//var pars = JSON.stringify(params);
		accion(turnoActual, miRol, "rendirse", params);
	}

	function confirmarTurno(turnoActual, miRol){ // Muestra un pop-up con botones de confirmar y cancelar
		if(turnoActual == miRol){
			$("#marcoConfirmar1").fadeTo("slow", 1.0);
			$("#marcoConfirmar1").css("z-index", "1");
			$("#opacidad").fadeTo("fast", 0.5);
			$("#opacidad").css("z-index", "0");
			$("#opacidad").css("display", "block");
		}
	}

	function cancelar(){ // Vuelve a la partida
		$("#opacidad").fadeTo("fast", 0.0);
		$(".marcoConfirmar").fadeTo("slow", 0.0);
		$("#opacidad").css("z-index", "-5");
	}

	function terminarTurno(miRol, turnoActual, p){ // Termina el turno del jugador. p tiene el idPartida, turnoActual y nTurno
		// Actualizar base de datos con el estado de la partida y enviar la solicitud de turno al contrincante
		accion(turnoActual, miRol, "finTurno", p);
		/*var siguienteRol;
		if(miRol == "Aventurero"){
			siguienteRol = "Master";
		}
		else 
			siguienteRol = "Aventurero";
		
		idInterval = setInterval(function(){ // setInterval devuelve un valor
			if(siguienteRol != turnoActual){					
				turnos(turnoActual, siguienteRol,"comprobarMiTurno", p);
				comprobar = true;
			}}
		, 5000);*/
	}
	
	function empezarTurno(){
		location.reload();
	}

	function spread($tam){
		nCartaMano = $tam;
		//var longitud = $(".cartaAventurero").width() + 5;
		//console.log(longitud);
		if($spread == false){
		for(i = 0; i < $tam; i++){
			//Desplazar 10 veces el mazo e ir dejando la carta de abajo estable
			for(j = i + 1; j < $tam; j++){
				$('#mano_' + j).animate({ left: '+=150' }, 120, "linear"); // Trasladar cartas del mazo salvo la última longitud*i pixeles
			}
		}
		}
		$spread = true;
	}

	function unspread($tam){
		if($spread == true){
			for(k = 1; k <= $tam; k++){
				//Desplazar 10 veces el mazo e ir dejando la carta de abajo estable
				for(l = 0 ; l <= k; l++){
					var x = $tam - j;
					$('#mano_' + l).animate({ left: '0' }, 300, "linear"); // Trasladar cartas del mazo salvo la última longitud*i pixeles
				}
			}
		}
		$spread = false;
	}
	
	function accion(turnoActual, miRol, nombreFuncion, p){ // Función que se encarga de llamar a una función genérica de php para cualquier método del modelo
		var funcion;
		console.log("Turno actual: " + turnoActual);
		console.log("Mi rol: " + miRol);
		if(turnoActual == miRol){
			if(!jQuery.isEmptyObject(p)){
				funcion = {nombre: nombreFuncion, llevaParams: "si", parametros: p};
			}
			else{
				funcion = {nombre: nombreFuncion, llevaParams: "no"};
			}
			console.log(funcion);
			$.ajax({
				type: "POST",
				url: "funcion.php",
				data: {"accion": funcion},
				datatype: "json",
				success: function(data){
					console.log("Éxito!");
					console.log(data);
					tratarResultados(turnoActual, miRol, nombreFuncion, p, data); // Si todo va bien, procesa los resultados
				},
				error: function(data){
					console.log("Ha habido un error");
					console.log(data["ok"]);
				}
			});
		}
	}
	
	function turnos(turnoActual, miRol, nombreFuncion, p){ // Función que se encarga de llamar a una función genérica de php para cualquier método del modelo
		var funcion;
		console.log("Turno actual: " + turnoActual);
		console.log("Mi rol: " + miRol);
		
		if(!jQuery.isEmptyObject(p)){
			funcion = {nombre: nombreFuncion, llevaParams: "si", parametros: p};
		}
		else{
			funcion = {nombre: nombreFuncion, llevaParams: "no"};
		}
		
		console.log(funcion);
		$.ajax({
			type: "POST",
			url: "funcion.php",
			data: {"accion": funcion},
			datatype: "json",
			success: function(data){
				console.log("Éxito! " + nombreFuncion);
				console.log("Data de " + nombreFuncion + ": " + data);
				tratarResultados(turnoActual, miRol, nombreFuncion, p, data); // Si todo va bien, procesa los resultados
			},
			error: function(data){
				console.log("Ha habido un error " + nombreFuncion);
			}
			
		});
		
	}
	
	function tratarResultados(turnoActual, miRol, nombreFuncion, p, data){ // Ir añadiendo ifs para tratar de forma distinta los resultados
		if(nombreFuncion == "sacarCarta"){
			if(data["ok"] == true){
				var nuevaCarta = document.getElementById('mano_' + p[1]);
				var nCartaCampo = data["resultAccion"].posicion  - 1;
				console.log(nCartaCampo);
				nuevaCarta.setAttribute("class", "cartaAventurero");
				nuevaCarta.setAttribute("id", "campoj1_" + nCartaCampo);
				nuevaCarta.setAttribute("onmouseover", "unspread('" + nCartaMano + "')");
				nuevaCarta.removeAttribute("ondblclick");
				nuevaCarta.setAttribute("ondblclick", "seleccionarTarget('"+nCartaCampo+"', '"+true+"', '"+miRol+"', "+false+")");
				//nuevaCarta.style.position = "relative";
				
				console.log("Nueva coordenada x: ", x);
				nuevaCarta.style.left = x;
				posicion = nCartaCampo;
				//x= x + 155*nCartaCampo;
//				//nuevaCarta.style.float = "left";
//				nuevaCarta.style.left = x;
//				x+= 155;
				//document.getElementById('cartas').style.display = "inline-block";
				document.getElementById('cartas').appendChild(nuevaCarta);
				nuevaEntrada = document.createElement('li');
				nuevaEntrada.innerHTML = data["resultAccion"].descripcion;
				document.getElementById('log').appendChild(nuevaEntrada);			
				
				$("mano_" + p[1]).remove();
				$(".botonesHabilidades").prop("disabled", false);
				$(".botonesHabilidades").attr("id", "activo");
				cerrarSpreadParcial(p[1]);
				var i = 0;
				$('.cartaAventureroMano').each( function() {
					if(!$(this).is("#mano_" + i)){
						var params = p;
						$(this).attr("id", "mano_" + i);
						var params = [p[0], i];
						var pars = JSON.stringify(params);
						$(this).attr("ondblclick", "accion('"+turnoActual+"', '"+miRol+"', '"+nombreFuncion+"', "+pars+")");
					}
				    i++;
				});
				//Ahora cerramos el spread
				
				nCartaMano--;
			}
			else{ // Tratar error
				$("#errorPartida").text("No puedes sacar más cartas al campo.");
				$("#errorPartida").fadeTo("fast", 1.0);
				setTimeout(function(){ $("#errorPartida").fadeTo("slow", 0.0); }, 3000);
			}
		}
		if(nombreFuncion == "finTurno"){
			$("#opacidad").fadeTo("fast", 0.0);
			$("#marcoConfirmar1").fadeTo("slow", 0.0);
			$("#opacidad").css("z-index", "-5");
			location.reload();
			
		}
		if(nombreFuncion == "comprobarMiTurno"){
			if(data["ok"] == true){
				//comprobar = true;
				clearInterval(idInterval);
				$("#marcoConfirmar2").fadeTo("slow", 1.0);
				$("#marcoConfirmar2").css("z-index", "1");
				$("#opacidad").fadeTo("fast", 0.5);
				$("#opacidad").css("z-index", "0");
				$("#opacidad").css("display", "block");
			}
		}
		if(nombreFuncion == "getMovimientos"){
			if(data["ok"] == true){
				// Llamar a función auxiliar que reproduzca movimientos
				var result = data["resultAccion"];
				reproducirMovimientos(result);
			}
		}
		if(nombreFuncion == "usarHabilidad"){
			// Por implementar
			var params = JSON.parse(p);
			var result = data["resultAccion"]
			var tipo = params[1].tipo;
			//console.log(tipo);
			switch(tipo){
				case "Ataque":
			        reproducirAtaque("campoj1_", params, result, result.descripcion);
			        break;
			    case "Curacion":
			    	reproducirCura("campoj1_", params, result, result.descripcion);
			        break;
			    case "Energia":
			    	reproducirEnergia("campoj1_", params, result, result.descripcion);
			        break;
			    case "RoboVida":
			    	reproducirRobarVida("campoj1_", params, result, result.descripcion)
			        break;
			}			
		}
		if(nombreFuncion == "rendirse"){
			// Mostrar pop up con botones de redirección a ranking.php
			$("#marcoConfirmar4").fadeTo("slow", 1.0);
			$("#marcoConfirmar4").css("z-index", "1");
			$("#opacidad").fadeTo("fast", 0.5);
			$("#opacidad").css("z-index", "0");
			$("#opacidad").css("display", "block");
			var ganador = data["resultAccion"].ganador;
			var perdedor = data["resultAccion"].perdedor;
			// p[1] es el email del jugador que se ha rendido
			$("#perdedor").text(perdedor + " se ha rendido. Le guardaremos rencor.")
			$("#ganador").text("El ganador de la partida por lo tanto es " + ganador + ".");
//			setTimeout(function(){ 
//				var url = "/Overclock/ranking.php";
//				window.location.href = url; 
//			}, 3000);
		}
	}
	
	function reproducirEnergia(campo, p, result, descripcion){
		var habilidad = p[1];
		// p[0] es la carta origen, p[1] es la habilidad, p[2] es el targetAmigo, p[3] es el rolTarget, p[4] es la carta destino
			//console.log("Posicion destino:" + p[2]);
			//$("#campoj1_" + p[0]).effect("shake", {direction: "up"}, 2000);
		if(result == false){
			$("#errorPartida").text("Selecciona un objetivo antes de restaurar energía.");
			$("#errorPartida").fadeTo("fast", 1.0);
			setTimeout(function(){ $("#errorPartida").fadeTo("slow", 0.0); }, 3000);
		}
		else{
			var j1;
			var cartas;
			if(campo == "campoj1_"){
				j1 = "campoj1_";
				cartas = ".cartaAventurero";
			}else{
				cartas = ".cartaOtro";
				j1 = "campoj2_";
			}
			setTimeout(function(){
				var textoF = parseInt(p[1].valor);
				var destino = p[2];
				var energiaAnterior = parseInt($("#" + j1 + destino + " #energia").text());
				
				var total = $("#" + j1 + destino + " #energia").text() + parseInt(textoF);
				if(total > 10) 
					total = 10;
				$("#" + j1 + destino + " #energia").text(parseInt(total));
				var energia = $("<p>");
				energia.text("+" + textoF);
				energia.css("position", "absolute");
				energia.css("font-size", "50px");
				energia.css("color", "#63d2ea");
				energia.css("top", "-120px");
				energia.css("left", "60px");
				$("#" + j1 + destino + " #energia").append(energia);
				energia.slideUp("slow");
			});
			
			nuevaEntrada = document.createElement('li');
			nuevaEntrada.innerHTML = descripcion;
			document.getElementById('log').appendChild(nuevaEntrada);
			$("#log").animate({ scrollTop: $(document).height() }, "slow");
			var box = document.getElementById('Box');
		}
	}
	
	function reproducirCura(campo, p, result, descripcion){
		var habilidad = p[1];
		// p[0] es la carta origen, p[1] es la habilidad, p[2] es el targetAmigo, p[3] es el rolTarget, p[4] es la carta destino
			//console.log("Posicion destino:" + p[2]);
			//$("#campoj1_" + p[0]).effect("shake", {direction: "up"}, 2000);
		if(result == false){
			$("#errorPartida").text("Selecciona un objetivo antes de curar.");
			$("#errorPartida").fadeTo("fast", 1.0);
			setTimeout(function(){ $("#errorPartida").fadeTo("slow", 0.0); }, 3000);
		}
		else if (result.valor == false){
			nuevaEntrada = document.createElement('li');
			nuevaEntrada.innerHTML = result.descripcion;
			document.getElementById('log').appendChild(nuevaEntrada);
			$("#log").animate({ scrollTop: $(document).height() }, "slow");
			var box = document.getElementById('Box');
		}
		else{
			var j1;
			var cartas;
			if(campo == "campoj1_"){
				j1 = "campoj1_";
				cartas = ".cartaAventurero";
			}else{
				cartas = ".cartaOtro";
				j1 = "campoj2_";
			}

			if(!$("#" + j1 + p[0] + " #energia").empty()){
				var e = parseInt($("#" + j1 + p[0] + " #energia"));
				var e = e - parseInt(p[1].coste);
				$("#" + j1 + p[0] + " #energia").text(e);
			}
			setTimeout(function(){
				if(habilidad.area == "No"){
					var textoF = parseInt(p[1].valor);
					var destino = p[2];
					var vidaAnterior = parseInt($("#" + j1 + destino + " #hp").text());
					$("#" + j1 + destino + " #hp").text(parseInt(vidaAnterior) + parseInt(textoF));
					var sanacion = $("<p>");
					sanacion.text("+" + textoF);
					sanacion.css("position", "absolute");
					sanacion.css("font-size", "50px");
					sanacion.css("color", "green");
					sanacion.css("top", "-120px");
					sanacion.css("left", "60px");
					$("#" + j1 + destino + " #hp").append(sanacion);
					sanacion.slideUp("slow");
					//sanacion.fadeTo("slow", 0.0);
				}
				else{
					var i = 0;
					$(cartas).each( function() {
						var textoF = p[1].valor
						var vidaActual = parseInt($(this).children("#hp").text(), 10);
						vidaActual += parseInt(textoF);
						
						// mirar lo de hpMax
						var hp = $(this).children("#hp").text();
						if($(this).children("#hp").text() != " "){
							$(this).children("#hp").text(vidaActual);
							
							var sanacion = $("<p>");
							sanacion.text("+" + textoF);
							sanacion.css("position", "absolute");
							sanacion.css("font-size", "50px");
							sanacion.css("color", "green");
							sanacion.css("top", "-120px");
							sanacion.css("left", "60px");
							$("#" + j1 + i + " #hp").append(sanacion);
							sanacion.slideUp("slow");
						}
						i++;
					});
				}
			}, 500);
			
			
			nuevaEntrada = document.createElement('li');
			nuevaEntrada.innerHTML = descripcion;
			document.getElementById('log').appendChild(nuevaEntrada);
			$("#log").animate({ scrollTop: $(document).height() }, "slow");
		
		}
	}
	
	function reproducirAtaque(campo, p, result, descripcion){
		var habilidad = p[1];
		//carta[6], h, targetAmigo, rolTarget, targetEnemigo
		// p[0] es la carta origen, p[1] es la habilidad, p[2] es el targetAmigo, p[3] es el rolTarget, p[4] es la carta destino
		if(result == false){
			$("#errorPartida").text("Selecciona un objetivo antes de atacar.");
			$("#errorPartida").fadeTo("fast", 1.0);
			setTimeout(function(){ $("#errorPartida").fadeTo("slow", 0.0); }, 3000);
		}
		else if (result.valor == false){
			nuevaEntrada = document.createElement('li');
			nuevaEntrada.innerHTML = result.descripcion;
			document.getElementById('log').appendChild(nuevaEntrada);
			$("#log").animate({ scrollTop: $(document).height() }, "slow");
			var box = document.getElementById('Box');
		}
		else{
			var j1, j2;
			var cartas;
			var desp1, desp2;
			if(campo == "campoj1_"){
				j1 = "campoj1_";
				j2 = "campoj2_";
				cartas = ".cartaOtro";
				desp1 = '-=20';
				desp2 = '+=20';
			}
			else{
				cartas = ".cartaAventurero";
				j2 = "campoj1_";
				j1 = "campoj2_";
				desp2 = '-=20';
				desp1 = '+=20';
			}
			// Animaciones de la carta origen
			$("#" + j1 + p[0]).animate({ top: desp1 }, 120, "linear");
			$("#" + j1 + p[0]).animate({ top: desp2 }, 120, "linear");
			if(!$("#" + j1 + p[0] + " #energia").empty()){
				var e = parseInt($("#" + j1 + p[0] + " #energia"));
				var e = e - parseInt(p[1].coste);
				$("#" + j1 + p[0] + " #energia").text(e);
			}
			setTimeout(function(){
				if(habilidad.area == "No"){
					// Animaciones de la carta destino
					$("#" + j2 + p[4]).animate({ top: desp1 }, 120, "linear");
					$("#" + j2 + p[4]).animate({ top: desp2 }, 120, "linear");
					// Le actualizamos la vida a la carta destino
					var textoF = parseInt(p[1].valor);
					console.log("Posición afectada: " + p[2]);
					var vidaAnterior = parseInt($("#" + j2 + p[4] + " #hp").text());
					vidaAnterior -= parseInt(habilidad.valor);
					console.log("Daño causado: " + textoF);
					console.log("Vida restante: " + result.vidaActual);
					$("#" + j2 + p[4] + " #hp").text(vidaAnterior);
					// Preparamos el texto flotante
					var dmg = $("<p>");
					dmg.text("-" + textoF);
					dmg.css("position", "absolute");
					dmg.css("font-size", "50px");
					dmg.css("color", "red");
					dmg.css("top", "-120px");
					dmg.css("left", "60px");
					// Y lo colocamos
					$("#" + j2 + p[4] + " #hp").append(dmg);
					// Animaciones del texto flotante
					dmg.effect("shake");
					dmg.fadeTo("slow", 0.0);
					
					// Si la carta destino muere, explota y la eliminamos
					if(vidaAnterior <= 0 || vidaAnterior == "NaN"){
						$("#" + j2 + p[4]).effect("explode");
						 
						targetEnemigo = -1;
						$("#" + j2 + i).delay(1000).remove(); 
						var j = 0;
						$('.cartaOtro').each( function() {
							$(this).attr("id", "" + j2 + j);						
						    j++;
						});
					}
				}
				else{
					var i = 0;
					$(cartas).each( function() {
						if($(this).children("#hp").text() != " "){
							$(this).animate({ top: desp1 }, 120, "linear");
							$(this).animate({ top: desp2 }, 120, "linear");
							var textoF = parseInt(p[1].valor);
							var vidaActual = parseInt($(this).children("#hp").text()) - textoF;
							if (vidaActual < 0) vidaActual = 0;
							$(this).children("#hp").text(vidaActual);
							console.log("Vida actual: " + vidaActual);
							
							var dmg = $("<p>");
							dmg.text("-" + textoF);
							dmg.css("position", "absolute");
							dmg.css("font-size", "50px");
							dmg.css("color", "red");
							dmg.css("top", "-120px");
							dmg.css("left", "60px");
							dmg.css("display", "none");
							$("#" + j2 + i + " #hp").append(dmg);
							dmg.effect("shake");
							dmg.fadeTo("slow", 0.0);
							if(vidaActual <= 0){
								$("#" + j2 + i).effect("explode");
								//setTimeout(function(){
									$("#" + j2 + i).delay(1000).remove(); 
								//}, 1000);
								var j = 0;
								targetEnemigo = -1;
								$('.cartaOtro').each( function() {
									$(this).attr("id", j2 + j);						
								    j++;
								});
							}
							i++;
						}
					});
				}
				
				
			}, 500);
			
			
			nuevaEntrada = document.createElement('li');
			nuevaEntrada.innerHTML = descripcion;
			document.getElementById('log').appendChild(nuevaEntrada);
			$("#log").animate({ scrollTop: $(document).height() }, "slow");
		}
	}
	
	function reproducirRobarVida(campo, p, result, descripcion){
		var habilidad = p[1];
		//carta[6], h, targetAmigo, rolTarget, targetEnemigo
		// p[0] es la carta origen, p[1] es la habilidad, p[2] es el targetAmigo, p[3] es el rolTarget, p[4] es la carta destino
		if(result == false){
			$("#errorPartida").text("Selecciona un objetivo antes de atacar.");
			$("#errorPartida").fadeTo("fast", 1.0);
			setTimeout(function(){ $("#errorPartida").fadeTo("slow", 0.0); }, 3000);
		}
		else if (result.valor == false){
			nuevaEntrada = document.createElement('li');
			nuevaEntrada.innerHTML = result.descripcion;
			document.getElementById('log').appendChild(nuevaEntrada);
			$("#log").animate({ scrollTop: $(document).height() }, "slow");
			var box = document.getElementById('Box');
		}
		else{
			var j1, j2;
			var cartas;
			var desp1, desp2;
			if(campo == "campoj1_"){
				j1 = "campoj1_";
				j2 = "campoj2_";
				cartas = ".cartaOtro";
				desp1 = '-=20';
				desp2 = '+=20';
			}
			else{
				cartas = ".cartaAventurero";
				j2 = "campoj1_";
				j1 = "campoj2_";
				desp2 = '-=20';
				desp1 = '+=20';
			}
			// Animaciones de la carta origen
			$("#" + j1 + p[0]).animate({ top: desp1 }, 120, "linear");
			$("#" + j1 + p[0]).animate({ top: desp2 }, 120, "linear");
			if(!$("#" + j1 + p[0] + " #energia").empty()){
				var e = parseInt($("#" + j1 + p[0] + " #energia"));
				var e = e - parseInt(p[1].coste);
				$("#" + j1 + p[0] + " #energia").text(e);
			}
			setTimeout(function(){
				// Animaciones de la carta destino
				$("#" + j2 + p[4]).animate({ top: desp1 }, 120, "linear");
				$("#" + j2 + p[4]).animate({ top: desp2 }, 120, "linear");
				// Le actualizamos la vida a la carta destino
				var textoF = parseInt(p[1].valor);
				console.log("Posición afectada: " + p[2]);
				var vidaAnterior = parseInt($("#" + j2 + p[4] + " #hp").text());
				vidaAnterior -= parseInt(habilidad.valor);
				console.log("Daño causado: " + textoF);
				console.log("Vida restante: " + result.vidaActual);
				$("#" + j2 + p[4] + " #hp").text(vidaAnterior);
				// Preparamos el texto flotante
				var dmg = $("<p>");
				dmg.text("-" + textoF);
				dmg.css("position", "absolute");
				dmg.css("font-size", "50px");
				dmg.css("color", "red");
				dmg.css("top", "-120px");
				dmg.css("left", "60px");
				// Y lo colocamos
				$("#" + j2 + p[4] + " #hp").append(dmg);
				// Animaciones del texto flotante
				dmg.effect("shake");
				dmg.fadeTo("slow", 0.0);
				
				// Ahora nos curamos
				var textoF = parseInt(p[1].valor);
				var destino = p[0];
				var vidaAnterior = parseInt($("#" + j1 + destino + " #hp").text());
				$("#" + j1 + destino + " #hp").text(parseInt(vidaAnterior) + parseInt(textoF));
				var sanacion = $("<p>");
				sanacion.text("+" + textoF);
				sanacion.css("position", "absolute");
				sanacion.css("font-size", "50px");
				sanacion.css("color", "green");
				sanacion.css("top", "-120px");
				sanacion.css("left", "60px");
				$("#" + j1 + destino + " #hp").append(sanacion);
				sanacion.slideUp("slow");
				
				// Si la carta destino muere, explota y la eliminamos
				if(result.vidaActual <= 0){
					$("#" + j2 + p[4]).effect("explode");
					 
					targetEnemigo = -1;
					var j = 0;
					$('.cartaOtro').each( function() {
						$(this).attr("id", "" + j2 + j);						
					    j++;
					});
				}				
				
			}, 500);
			
			nuevaEntrada = document.createElement('li');
			nuevaEntrada.innerHTML = descripcion;
			document.getElementById('log').appendChild(nuevaEntrada);
			$("#log").animate({ scrollTop: $(document).height() }, "slow");
		}
	}
	
	function reproducirMovimientos(result){
		var nA = result.nA;
		var nM = result.nM;
		var movimientos = result.movimientos;
		if(movimientos.length > 0)
		for(i = 0; i < movimientos.length; i++){
			var mov = movimientos[i]; // $mov tiene nombreFuncion, descripcion, carta, hp y campo
			if(mov.nombre == "sacarCarta"){
				var n;
				var claseP;
				var claseImg;
				if(mov.campo == "Aventurero"){
					n = nA - 1;
					claseP = "negra";
					claseImg = "carta"
				}
				else{
					claseP = "blanca";
					n = nM - 1;
					claseImg = "cartaJefe"
				}

				var c = mov.carta;
				//claseImg = getMarcoCarta(c);
				var nuevaCarta = document.createElement('div');
				nuevaCarta.id = 'campoj2_' + n;
				nuevaCarta.className = 'cartaOtro';
				
				var img1 = document.createElement('img');
				img1.className = 'personajeAventurero';
				img1.setAttribute("src", "img/cartas/" + c.imagen +".png");
				var img2 = document.createElement('img');
				img2.className = 'marcoAventurero';
				img2.setAttribute("src", "img/"+claseImg+".png");
				console.log(claseImg);
				var p1 = document.createElement('p');
				p1.className = claseP;
				p1.id = 'nombreCarta';
				p1.innerHTML = c.nombre;
				var p2 = document.createElement('p');
				p2.className = claseP;
				p2.id = 'hp';
				p2.innerHTML = c.hp;
				
				nuevaCarta.appendChild(img1);
				nuevaCarta.appendChild(img2);
				nuevaCarta.appendChild(p1);
				nuevaCarta.appendChild(p2);
				// Falta colocar el atributo onclick otra vez con nuevos parámetros
				//nuevaCarta.attr("onclick", "apareceCarta('"+claseP+"', '"+miRol+"', '"+mov.nombre+"', "+pars+")");
				document.getElementById('cartaJefe').appendChild(nuevaCarta);
				
				// Ahora añadimos la nueva entrada al log de acciones
				nuevaEntrada = document.createElement('li');
				nuevaEntrada.innerHTML = mov.descripcion;
				document.getElementById('log').appendChild(nuevaEntrada);
				$("#log").animate({ scrollTop: $(document).height() }, "slow");
			}
			if(mov.nombre == "atacar"){
				console.log("He entrado en tratar movimiento atacar");
				console.log(result);
				var habilidad = mov.habilidad;
				// p[0] es la carta origen, p[1] es la habilidad, p[2] es el targetAmigo, p[3] es el rolTarget, p[4] es la carta destino
				var p = [parseInt(mov.posOrigen), habilidad, 0, mov.campo, parseInt(mov.posDestino)];
				var result;
				reproducirAtaque("campoj2_", p, result, mov.descripcion);
			}
			if(mov.nombre == "curar"){
				console.log("He entrado en tratar movimiento curar");
				console.log(result);
				var habilidad = mov.habilidad;
				console.log("Pos_origen: " + parseInt(mov.posOrigen));
				console.log("Pos_destino: " + parseInt(mov.posDestino));
				// p[0] es la carta origen, p[1] es la habilidad, p[2] es el targetAmigo, p[3] es el rolTarget, p[4] es la carta destino
				var p = [parseInt(mov.posDestino), habilidad, 0, mov.campo, parseInt(mov.posOrigen)];
				var result;
				reproducirCura("campoj2_", p, result, mov.descripcion);
			}
			if(mov.nombre == "energia"){
				console.log("He entrado en tratar movimiento energia");
				console.log(result);
				var habilidad = mov.habilidad;
				console.log("Pos_origen: " + parseInt(mov.posOrigen));
				console.log("Pos_destino: " + parseInt(mov.posDestino));
				// p[0] es la carta origen, p[1] es la habilidad, p[2] es el targetAmigo, p[3] es el rolTarget, p[4] es la carta destino
				var p = [parseInt(mov.posDestino), habilidad, 0, mov.campo, parseInt(mov.posOrigen)];
				var result;
				reproducirEnergia("campoj2_", p, result, mov.descripcion);
			}
			if(mov.nombre == "robarVida"){
				console.log("He entrado en tratar movimiento robar vida");
				console.log(result);
				var habilidad = mov.habilidad;
				console.log("Pos_origen: " + parseInt(mov.posOrigen));
				console.log("Pos_destino: " + parseInt(mov.posDestino));
				// p[0] es la carta origen, p[1] es la habilidad, p[2] es el targetAmigo, p[3] es el rolTarget, p[4] es la carta destino
				var p = [parseInt(mov.posOrigen), habilidad, 0, mov.campo, parseInt(mov.posDestino)];
				var result;
				reproducirRobarVida("campoj2_", p, result, mov.descripcion)
			}
			if(mov.nombre == "rendirse"){
				$("#marcoConfirmar4").fadeTo("slow", 1.0);
				$("#marcoConfirmar4").css("z-index", "1");
				$("#confirmar4").css("height", "150");
				$("#opacidad").fadeTo("fast", 0.5);
				$("#opacidad").css("z-index", "0");
				$("#opacidad").css("display", "block");
				$("#ganador").text("Has ganado la partida.");
				$("#perdedor").text(mov.descripcion);
				// p[1] es el email del jugador que se ha rendido
				var turnoActual = "j2";
				var miTurno = "j2";
				var p = [];
				accion(turnoActual, miTurno, "borrarPartida", p);
//				setTimeout(function(){ 
//					var url = "/Overclock/ranking.php";
//					window.location.href = url; 
//				}, 3000);
				
			}
		}
	}
	
	function getMarcoCarta(carta){
		var tipo = carta.tipo;
		var marco;
		switch(tipo){
		case "Boss":
			marco = "cartaJefe";
			break;
		case "Personaje":
			marco = "carta";
			break;
		case "Evento":
			marco = "cartaEvento";
			break;
		case "Objeto":
			marco = "cartaItem";
			break;
		}
	}
	
	// eclipse pdt xdebug, configurar xampp para habilitar modo debug. O plug in para chrome
	function cerrarSpreadParcial(n){
		for(j = n + 1; j < nCartaMano; j++){
			$('#mano_' + j).animate({ left: '-=155' }, 120, "linear"); // Trasladar cartas del mazo salvo la última longitud*i pixeles
		}
	}
	
	 //Muestra la ayuda en la pantalla
	 function mostrarAyuda(){ 
	  
		  $("#opacidad").fadeTo("fast", 0.5);
		  $("#opacidad").css("z-index", "0");
		  $("#opacidad").css("display", "block");
		  $("#marcoConfirmar5").fadeTo("fast", 1.0);
		  $("#marcoConfirmar5").css("z-index", "1");
		  $("#marcoConfirmar5").animate({ left: '-800' }, 500, "linear");
	 
	 }

	 //Quita la ayuda de la pantalla
	 function cerrarAyuda(){ 
	  
		  $("#marcoConfirmar5").animate({ left: '+800' }, 500, "linear");
		  $("#opacidad").fadeTo("fast", 0.0);
		  $(".marcoConfirmar").fadeTo("fast", 0.0);
		  $("#opacidad").css("z-index", "-5");
	  
	 }
