$(document).ready(function(){
	$("#user").change(function(){
		if(userValido($("#user").val())){
			if(correoValido($("#email").val()) && passValida($("#pass1").val(),$("#pass2").val())){
				$("#botonRegistro").attr("disabled",false);
			}
			else{
				$("#botonRegistro").attr("disabled",true);
			}
		}
		else{
			$("#botonRegistro").attr("disabled",true);
		}
	});
	
	$("#email").change(function(){
		if(correoValido($("#email").val())){
			$("#mailIcon").attr("src","img/correcto.png");
			$("#mailIcon").show();
			if(userValido($("#user").val()) && passValida($("#pass1").val(),$("#pass2").val())){
				$("#botonRegistro").attr("disabled",false);
			}
			else{
				$("#botonRegistro").attr("disabled",true);
			}
		}
		else{
			$("#mailIcon").attr("src","img/eliminar.png");
			$("#mailIcon").show();
			$("#botonRegistro").attr("disabled",true);
		}
	});
	
	$("#pass1").change(function(){
		if(passValida($("#pass1").val(),$("#pass2").val())){
			$("#passIcon").attr("src","img/correcto.png");
			$("#passIcon").show();
			if(correoValido($("#email").val()) && userValido($("#user").val())){
				$("#botonRegistro").attr("disabled",false);
			}
			else{
				$("#botonRegistro").attr("disabled",true);
			}	
		}
		else{
			$("#passIcon").attr("src","img/eliminar.png");
			$("#passIcon").show();
			$("#botonRegistro").attr("disabled",true);
		}
	});
	
	$("#pass2").change(function(){
		if(passValida($("#pass1").val(),$("#pass2").val())){
			$("#passIcon").attr("src","img/correcto.png");
			$("#passIcon").show();
			if(correoValido($("#email").val()) && userValido($("#user").val())){
				$("#botonRegistro").attr("disabled",false);
			}
			else{
				$("#botonRegistro").attr("disabled",true);
			}	
		}
		else{
			$("#passIcon").attr("src","img/eliminar.png");
			$("#passIcon").show();
			$("#botonRegistro").attr("disabled",true);
		}
	});
});

function correoValido(mail){
	var expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!expr.test(mail))
		return false;
	return true;
}

function passValida(pass1,pass2){
	if (pass1 == pass2 && (pass1 != "") && (pass2 != ""))
		return true;
	else
		return false;
}

function userValido(user){
	if(user == "")
		return false;
	else
		return true;
}
