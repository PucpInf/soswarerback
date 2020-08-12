$(document).ready(function() {
	$.ajax({
		url: "http://200.16.7.152/api/convocatoria/getDatos"
	}).then(function(data) {
		$('#titulo').append(data.body.titulo);
		sessionStorage.setItem("titulo",data.body.titulo);
		sessionStorage.setItem("idConvocatoria",data.body.idConvocatoria);
		var dpto = data.body.departamento;
		var sec = data.body.seccion;
		var seccion = 'Seccion ' + dpto + ' ' + sec;
		sessionStorage.setItem("seccion",seccion);
		$('#seccion').append(seccion);
		var line = 'El Departamento Académico de ' + dpto + ' de la Pontificia Universidad Católica del Perú (PUCP) invita a participar en el concurso para la asignación de:';
		$('#intro').append(line);
		var span = '(' + data.body.cantidad + ') ' + 'plaza de ' + data.body.puestoTrabajo + ' para la ' + seccion;
		$('#plazas').append(span);
		var array = data.body.requisitos;
		$.each(array,  function(i,val) {
			var datos = '<li>' + val.requisito + '</li>';
			$('#requisitos').append(datos);
		});
		var docs = data.body.documentos;
		$.each(docs, function(i,val) {
			var doc = '<li>' + val.documento + '</li>';
			$('#documentos').append(doc);
		});
		var resp = data.body.responsabilidades;
		$.each(resp, function(i,val) {
			var r = '<li>' + val.responsabilidad + '</li>';
			$('#responsabilidades').append(r);
		});
		var benefit = data.body.beneficios;
		$.each(benefit, function(i,val) {
			var b = '<li>' + val.beneficio + '</li>';
			$('#beneficios').append(b);
		});
		var evalu = data.body.evaluacion;
		$.each(evalu, function(i,val) {
			var b = '<li>' + val.evaluacion + '</li>';
			$('#evaluacion').append(b);
		});
		var cierreConvocatoria = data.body.fechaFin;
		var preseleccionados = data.body.fechaPreSeleccion;
		var resultadosFinales = data.body.fechaResultado;
		var fechaInicio = data.body.fechaInicio;
		var cierre = '<li>Cierre de la convocatoria: ' + cierreConvocatoria + '</li>';
		$('#cronograma').append(cierre);
		var presele = '<li>Publicación de candidatos preseleccionados: ' + preseleccionados + '</li>';
		$('#cronograma').append(presele);
		var finales = '<li>Publicacion de resultados finales: ' + resultadosFinales + '</li>';
		$('#cronograma').append(finales);
		var inicio = '<li>Inicio de contrato: ' + fechaInicio + '</li>';
		$('#cronograma').append(inicio);
		var anexo = data.body.anexo;
		var correo = data.body.correo;
		var consulta = 'Cualquier consulta dirigirse a la secretaría de la ' + seccion + ', ' +
		'al teléfono (51-1)–6262000, anexo ' + anexo + 'o al siguiente correo electrónico: ';
		$('#consulta').append(consulta);
		$('#correo').append(correo);
	});
});