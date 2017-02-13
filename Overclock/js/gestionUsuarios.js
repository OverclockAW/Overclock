function borrar(id, email){
	$.ajax({
		type: "POST",
		url: "borrar.php",
		data: { "email" : email},
		success: function(data){
			console.log(data);
			if(data == true)
				document.getElementById('usuarios').deleteRow(id);
		},
		error: function (data){
			console.log("He entrado en error");
		}
	});
}

function banear(id, email){
	$.ajax({
		type: "POST",
		url: "banear.php",
		data: { "email" : email},
		success: function(data){
			console.log(data);
			if(data == "Si"){
				document.getElementById('ban_'+id).innerHTML = "Si";
			}
			else if(data == "No"){
				document.getElementById('ban_'+id).innerHTML = "No";
			}
		},
		error: function (data){
			console.log("He entrado en error");
		}
	});
}
