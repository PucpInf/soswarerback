$(document).ready(function() {
	$.ajax({
		url: "http://200.16.7.152/api/usuario/listaProfesores"
	}).then(function(data) {
		var array = data.data;
		//codigo para procesar data
		$.each(array,  function(i,val) {
			var datos = '<li>' + val.nombre + '</li>';
			$('#documentos').append(datos);
		});
	});
});