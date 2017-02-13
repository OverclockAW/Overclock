function borrar(id, nombre){
	$.ajax({
		type: "POST",
		url: "borrarCarta.php",
		data: { "nombre" : nombre},
		success: function(data){
			document.getElementById('tablaCartas').deleteRow(id);
		}
	});
}