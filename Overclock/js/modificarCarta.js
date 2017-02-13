$(document).ready(function(){	
	$("#energiaCarta").change(function(){
		if(energiaValida($("#energiaCarta").val())){
			$("#energiaIcon").attr("src","img/ok.png");
			if(activarBoton($("#nombreCarta").val(),$("#energiaCarta").val(),$("#hpCarta").val(),$("#tipoCarta").val())){
				$("#modButton").attr("disabled",false);
				alert("Se activo el boton");
			}
			else{
				$("#modButton").attr("disabled",true);
				alert("Se desactivo el boton");
				$("#modButton").attr("disabled",true);
				alert("Se desactivo el boton");
			}
		}
		else{
			$("#energiaIcon").attr("src","img/eliminar.png");
			alert("Energia oncorrecta");
			$("#modButton").attr("disabled",true);
			alert("Se desactivo el boton");
		}
	});
	
	$("#hpCarta").change(function(){
		if(validarCompuesto($("#hpCarta").val(),$("#tipoCarta").val())){
			$("#hpIcon").attr("src","img/ok.png");
			if(activarBoton($("#nombreCarta").val(),$("#energiaCarta").val(),$("#hpCarta").val(),$("#tipoCarta").val())){
				$("#modButton").attr("disabled",false);
				alert("Se activo el boton");
			}
			else{
				$("#modButton").attr("disabled",true);
				alert("Se desactivo el boton");
			}
		}
		else{
			$("#hpIcon").attr("src","img/eliminar.png");
			alert("Hp incorrecta, para magias y eventos debe ser -1");
			$("#modButton").attr("disabled",true);
			alert("Se desactivo el boton");
		}
		
	})
	
	$("#tipoCarta").change(function(){
		if(validarCompuesto($("#hpCarta").val(),$("#tipoCarta").val())){
			$("#tipoIcon").attr("src","img/ok.png");
			if(activarBoton($("#nombreCarta").val(),$("#energiaCarta").val(),$("#hpCarta").val(),$("#tipoCarta").val())){
				$("#modButton").attr("disabled",false);
				alert("Se activo el boton");
			}
			else{
				$("#modButton").attr("disabled",true);
				alert("Se desactivo el boton");
			}
		}
		else{
			$("#tipoIcon").attr("src","img/eliminar.png");
			alert("Tipo incorrecto");
			$("#modButton").attr("disabled",true);
			alert("Se desactivo el boton");
		}
		
	})
	
	$("#modButton").click(function(){
		$("#nombreCarta").attr("disabled",false);
	})

});

function energiaValida(energia){
	if(energia > 0)
		return true;
	else
		return false;
}

function validarCompuesto(hp, tipo){
	if((tipo == "Personaje" && hp < 1) || (tipo == "Boss" && hp < 1) || (tipo == "Objeto" && hp != -1) || (tipo == "Evento" && hp != -1))
		return false;
	else
		return true;	
}

function activarBoton(nombre, energia,hp,tipo){
	if(nombre != "" && energiaValida(energia) && validarCompuesto(hp, tipo))
		return true;
	else
		return false;
}