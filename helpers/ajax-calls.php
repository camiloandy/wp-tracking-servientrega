<?php
	if ( ! defined( 'ABSPATH' ) ) exit;


//Obtener ciudades dependendiendo del departamento
add_action('wp_ajax_nopriv_wptrackingservientregaadminjs_ajax_getCities','getCities');
add_action('wp_ajax_wptrackingservientregaadminjs_ajax_getCities','getCities');
function getCities()
{
  $idDepartamento = $_POST['id_departamento'];
  $queryCiudades = new WP_Query(
    array('post_type'=>'ciudades', 
        'posts_per_page'=>-1, 
        'order'=>'ASC', 
        'orderby'=>'title',
        'tax_query' => array(
          array(
            'taxonomy' => 'departamentos',
            'field'    => 'term_id',
            'terms'    => $idDepartamento,
          ),
        ),
    )
  );

  if ($queryCiudades->have_posts()) {
    while($queryCiudades->have_posts()) {
      $queryCiudades->the_post();
      $ciudades = $ciudades . get_the_title() . ',';                      
    }
  }

  echo $ciudades;

  wp_die();
}
//END

//Codigo para guardar datos de configuracion de ciudad y departamento de origen.
add_action('wp_ajax_nopriv_wptrackingservientregaadminjs_ajax_saveDataCiudadDepartamentoOrigen','saveDataConfigCiudadDepartamentoOrigen');
add_action('wp_ajax_wptrackingservientregaadminjs_ajax_saveDataCiudadDepartamentoOrigen','saveDataConfigCiudadDepartamentoOrigen');

function saveDataConfigCiudadDepartamentoOrigen()
{
  $origenDepartamento = $_POST['departamento'];
  $origenCiudad = $_POST['ciudad'];

  update_option('origen_departamento', $origenDepartamento);
  update_option('origen_ciudad', $origenCiudad);

  wp_die();
}
//END

//Obtener ciudades dependendiendo del departamento
add_action('wp_ajax_nopriv_wptrackingservientregamainjs_ajax_getCitiesCheckout','getCitiesCheckout');
add_action('wp_ajax_wptrackingservientregamainjs_ajax_getCitiesCheckout','getCitiesCheckout');
function getCitiesCheckout()
{
  $idDepartamento = $_POST['id_departamento'];
  $queryCiudades = new WP_Query(
    array('post_type'=>'ciudades', 
        'posts_per_page'=>-1, 
        'order'=>'ASC', 
        'orderby'=>'title',
        'tax_query' => array(
          array(
            'taxonomy' => 'departamentos',
            'field'    => 'term_id',
            'terms'    => $idDepartamento,
          ),
        ),
    )
  );

  if ($queryCiudades->have_posts()) {
    while($queryCiudades->have_posts()) {
      $queryCiudades->the_post();
      $ciudades = $ciudades . get_the_title() . ',';                      
    }
  }

  echo $ciudades;

  wp_die();
}
//END

//Codigo para guardar datos de configuracion de usar los campos ciudad y departamento como lista.
add_action('wp_ajax_nopriv_wptrackingservientregaadminjs_ajax_saveDataCiudadDepartamentoLista','saveDataConfigCiudadDepartamentoLista');
add_action('wp_ajax_wptrackingservientregaadminjs_ajax_saveDataCiudadDepartamentoLista','saveDataConfigCiudadDepartamentoLista');

function saveDataConfigCiudadDepartamentoLista()
{
  $useListCityStates = $_POST['value'];
  update_option('use_list_city_states', $useListCityStates);
  wp_die();
}
//END