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

    var that = $(this).parent();

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
          $(that).find('img.load-img').css('display', 'inline');
          $(that).find('img.success-img').css('display', 'none');  
        },
        success: function(resp) {
          //console.log(resp);
          $(that).find('img.load-img').css('display', 'none');
          
          $(that).find('input[type=submit]').prop('disabled', false);
          $(that).find('img.success-img').css('display', 'inline');  
          $(that).find('span.messagge-template-error').css('display', 'none');
        }
      });
  });

  $('form#lista-ciudad-departamento input[type=submit]').click(function(evt){
    evt.preventDefault();

    var that = $(this).parent();

    var use_list_city_states = 0;
    if ($('#lista-ciudades-departamento-si').prop('checked') == true)
      use_list_city_states = 1;       

    $.ajax({
      url: wptrackingservientregaadminjs_vars.ajaxurl,
      type: 'post',
      data: {
        action: 'wptrackingservientregaadminjs_ajax_saveDataCiudadDepartamentoLista',
        value: use_list_city_states,
      }, 
        beforeSend: function() {
          console.log('guardando...');
          $('form#lista-ciudad-departamento img.load-img').css('display', 'inline');
          $('form#lista-ciudad-departamento img.success-img').css('display', 'none');  
        },
        success: function(resp) {
          console.log(resp);
          $('form#lista-ciudad-departamento img.load-img').css('display', 'none');
          $('form#lista-ciudad-departamento input[type=submit]').prop('disabled', false);
          $('form#lista-ciudad-departamento img.success-img').css('display', 'inline');  
          $('form#lista-ciudad-departamento span.messagge-template-error').css('display', 'none');
        }
      });
  });

  $('form#datos-acceso-servientrega input[type=submit]').click(function(evt){
    evt.preventDefault();

    var that = $(this).parent(),
        login = $('#login-servitentrega').val(),
        password = $('#password-servientrega').val(),
        codigoFacturacion = $('#codigo-facturacion-servientrega').val(),
        nombreCargue = $('#nombre-cargue-servientrega').val();

    $.ajax({
      url: wptrackingservientregaadminjs_vars.ajaxurl,
      type: 'post',
      data: {
        action: 'wptrackingservientregaadminjs_ajax_saveDataAccesoServientrega',
        dataLogin: login,
        dataPassword: password,
        dataCodigoFacturacion: codigoFacturacion,
        dataNombreCarque : nombreCargue
      }, 
        beforeSend: function() {
          console.log('guardando...');
          $(that).find('img.load-img').css('display', 'inline');
          $(that).find('img.success-img').css('display', 'none');  
        },
        success: function(resp) {
          console.log(resp);
          $(that).find('img.load-img').css('display', 'none');
          $(that).find('input[type=submit]').prop('disabled', false);
          $(that).find('img.success-img').css('display', 'inline');  
          $(that).find('span.messagge-template-error').css('display', 'none');
        }
      });
  });

});