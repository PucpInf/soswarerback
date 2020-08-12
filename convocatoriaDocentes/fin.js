$(document).ready(function(){
    $('#titulo').append(sessionStorage.getItem("titulo"));
    $('#seccion').append(sessionStorage.getItem("seccion"));
})