$(document).ready(function(){
	$('#titulo').append(sessionStorage.getItem("titulo"));
	$('#seccion').append(sessionStorage.getItem("seccion"));
})

$('#Enviar').click(function(e) {
	e.preventDefault();
	var date = $('#fechaNacimiento').val().split('-');
	var fechilla = date[1] + '/' + date[2] + '/' + date[0];
	var gg = $('input[name=genero]:checked').val();
	var secs;
	if (gg === 'Masculino') {
		secs = 'M'
	}
	else {
		secs = 'F'
	}
	$.ajax({
		type: "POST",
		url: "http://200.16.7.152/api/convocatoria/registraPostulante",
		data: {
			nombres: $('#nombres').val(),
			apPaterno: $('#apPaterno').val(),
			apMaterno: $('#apMaterno').val(),
			correo: $('#email').val(),
			telefono: $('#telefono').val(),
			dni: $('#dni').val(),
			fechaNacimiento: fechilla,
			sexo: secs,
			idConvocatoria: sessionStorage.getItem('idConvocatoria'),
			cv: sessionStorage.getItem('cv')
		},
		success: function(result) {
			console.log(JSON.stringify(result.data))
			alert('Postulante registrado correctamente');
			window.location.href="fin.html";
		},
		error: function(result) {
			console.log(JSON.stringify(result.data))
			alert('GG');
		}
	});
});

function readURL(input){
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		var base64;
		reader.onload = function (e) {
			$('#fileinput').attr('src', e.target.result);
			base64 = e.target.result;
			sessionStorage.setItem('cv',base64);
		};
		reader.readAsDataURL(input.files[0]);
	}
}