module_name = "producto_ingreso_2";

$(function() {
	console.log( "Document ready." );

$('#codigo').change(function() {
    codigo = $('#codigo').val();
    
    $.post('index.php?menu=' + module_name + '&rawmode=yes', {
	    menu:       module_name, 
	    rawmode:    'yes',
	    action: 	'consultar_disponibilidad',
	    campo: 		'codigo',
	    valor: 		codigo
	},	
	function (result) { // respuesta puede ser utilizado como objeto JSON
		console.log(result);	
		$("#mensaje").html(result.mensaje);				
		if(result.disponible == 0){
			$("#mensaje").css({ 'color': 'red', 'font-size': '100%' });				
	    }
	    if(result.disponible == 1){
			$("#mensaje").css({ 'color': 'green', 'font-size': '100%' });				
	    }
	    $('#mensaje').fadeIn("slow");		
	}); // Fin del post	
}); // Fin del codigo.change


$('#nombre').change(function() {
    nombre = $('#nombre').val();
    
    $.post('index.php?menu=' + module_name + '&rawmode=yes', {
	    menu:       module_name, 
	    rawmode:    'yes',
	    action: 	'consultar_disponibilidad',
	    campo: 		'nombre',
	    valor: 		nombre
	},	
	function (result) { // respuesta puede ser utilizado como objeto JSON
		console.log(result);	
		$("#mensaje").html(result.mensaje);				
		if(result.disponible == 0){
			$("#mensaje").css({ 'color': 'red', 'font-size': '100%' });				
	    }
	    if(result.disponible == 1){
			$("#mensaje").css({ 'color': 'green', 'font-size': '100%' });				
	    }
	    $('#mensaje').fadeIn("slow");		
	}); // Fin del post	
}); // Fin del nombre.change





}); // Document ready, estando dentro del documento ready se garantiza que los métodos se atan a los elementos una vez cargados en la página
