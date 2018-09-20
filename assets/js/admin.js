jQuery(document).ready(function($) {
	
	$('select#origen-departamento').change(function(){

    $('select#origen-ciudad').prop('disabled', true);
    
    var idDepartamento = $(this).val();
    $.ajax({
      url: wptrackingservientregaadminjs_vars.ajaxurl,
      type: 'post',
      data: {
        action: 'wptrackingservientregaadminjs_ajax_getCities',
        id_departamento: idDepartamento,
      }, 
      beforeSend: function() {
        console.log('trabajando...');
        $('img.loading-cities-admin').css('display', 'block');
      },
      success: function(resp) {
        var ciudades = resp.split(',');
        ciudades.pop();
        console.log(ciudades);

        $('select#origen-ciudad').html('');
        $('select#origen-ciudad').append('<option value="">Seleccionar</option>');  
        ciudades.forEach(function(element){
          $('select#origen-ciudad').append('<option value="'+element+'">'+element+'</option>');  
        });
        $('select#origen-ciudad').prop('disabled', false);
        $('img.loading-cities-admin').css('display', 'none');
      }
    });

  });

  $('form#origen-ciudad-departamento input[type=submit]').click(function(evt){
  	evt.preventDefault();
  	$('form#origen-ciudad-departamento span.messagge-template-error').css('display', 'none');

  	var origenDepartamento = $('form#origen-ciudad-departamento select#origen-departamento').val();
  	var origenCiudad = $('form#origen-ciudad-departamento select#origen-ciudad').val();

  	if(origenDepartamento == '' || origenCiudad == '') {
  		$('form#origen-ciudad-departamento span.messagge-template-error').css('display', 'inline');
  		return;
  	}

  	$.ajax({
        url: wptrackingservientregaadminjs_vars.ajaxurl,
        type: 'post',
        data: {
          action: 'wptrackingservientregaadminjs_ajax_saveDataCiudadDepartamentoOrigen',
          departamento: origenDepartamento,
          ciudad: origenCiudad,
        }, 
        beforeSend: function() {
          console.log('guardando...');
          $('form#origen-ciudad-departamento img.load-img').css('display', 'inline');
        },
        success: function(resp) {
          //console.log(resp);
          $('form#origen-ciudad-departamento img.load-img').css('display', 'none');
          
          $('form#origen-ciudad-departamento input[type=submit]').prop('disabled', false);
          $('form#origen-ciudad-departamento img.success-img').css('display', 'inline');  
          $('form#origen-ciudad-departamento span.messagge-template-error').css('display', 'none');
        }
      });
  });

});