$(document).ready(function(){
	$("#email").change(function(){
		if(correoValido($("#email").val())){
			$("#mailIcon").attr("src","img/correcto.png");
			$("#mailIcon").show();
			if(passValida($("#password").val(),$("#password2").val()))
				$("#modButton").attr("disabled",false);
			else{
				$("#modButton").attr("disabled",true);
			}
		}
		else{
			$("#mailIcon").attr("src","img/eliminar.png");
			$("#mailIcon").show();
			$("#modButton").attr("disabled",true);
		}
	});
	$("#password").change(function(){
		if(passValida($("#password").val(),$("#password2").val())){
			$("#passIcon").attr("src","img/correcto.png");
			$("#passIcon").show();
			if(correoValido($("#email").val()))
				$("#modButton").attr("disabled",false);
			else{
				$("#modButton").attr("disabled",true);
			}
		}
		else{
			$("#passIcon").attr("src","img/eliminar.png");
			$("#passIcon").show();
			$("#modButton").attr("disabled",true);
		}
	});
	$("#password2").change(function(){
		if(passValida($("#password").val(),$("#password2").val())){
			$("#passIcon").attr("src","img/correcto.png");
			$("#passIcon").show();
			if(correoValido($("#email").val()))
				$("#modButton").attr("disabled",false);
			else{
				$("#modButton").attr("disabled",true);
			}
		}
		else{
			$("#passIcon").attr("src","img/eliminar.png");
			$("#passIcon").show();
			$("#modButton").attr("disabled",true);
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

