jQuery(document).ready(function($) {  
  jQuery('#billing_state').change(function(){

  	$('#billing_city').prop('disabled', true);
  	$('#select2-billing_city-container').html('Cargando Ciudades...');
    
    var idDepartamento = $(this).val();
    $.ajax({
      url: wptrackingservientregamainjs_vars.ajaxurl,
      type: 'post',
      data: {
        action: 'wptrackingservientregamainjs_ajax_getCitiesCheckout',
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

        $('#billing_city').html('');
        $('#billing_city').append('<option value="">Seleccionar</option>');  
        ciudades.forEach(function(element){
          $('#billing_city').append('<option value="'+element+'">'+element+'</option>');  
        });
        $('#billing_city').prop('disabled', false);
        $('img.loading-cities-admin').css('display', 'none');
      }
    });
  });

  jQuery('#shipping_state').change(function(){

    $('#shipping_city').prop('disabled', true);
    $('#select2-shipping_city-container').html('Cargando Ciudades...');
    
    var idDepartamento = $(this).val();
    $.ajax({
      url: wptrackingservientregamainjs_vars.ajaxurl,
      type: 'post',
      data: {
        action: 'wptrackingservientregamainjs_ajax_getCitiesCheckout',
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

        $('#shipping_city').html('');
        $('#shipping_city').append('<option value="">Seleccionar</option>');  
        ciudades.forEach(function(element){
          $('#shipping_city').append('<option value="'+element+'">'+element+'</option>');  
        });
        $('#shipping_city').prop('disabled', false);
        $('img.loading-cities-admin').css('display', 'none');
      }
    });
  });


  jQuery("#billing_city_field").insertAfter('#billing_state_field');
  jQuery("#shipping_city_field").insertAfter('#shipping_state_field');
});