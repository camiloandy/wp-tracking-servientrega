<?php

if ( ! defined( 'ABSPATH' ) ) exit;

//Creando custom post type para lista de ciudades
if (!function_exists('create_post_type_departamentos_ciudades')) {
  function create_post_type_departamentos_ciudades() 
  {
    $labels = array(
      'name'               => _x( 'Lista de Ciudades', 'post type general name', 'your-plugin-textdomain' ),
      'singular_name'      => _x( 'Ciudades', 'post type singular name', 'your-plugin-textdomain' ),
      'menu_name'          => _x( 'Ciudades', 'admin menu', 'your-plugin-textdomain' ),
      'name_admin_bar'     => _x( 'Ciudades', 'add new on admin bar', 'your-plugin-textdomain' ),
      'add_new'            => _x( 'Agregar Ciudad', 'Color', 'your-plugin-textdomain' ),
      'add_new_item'       => __( 'Agregar nueva Ciudad', 'your-plugin-textdomain' ),
      'new_item'           => __( 'Nueva Ciudad', 'your-plugin-textdomain' ),
      'edit_item'          => __( 'Editar Ciudad', 'your-plugin-textdomain' ),
      'view_item'          => __( 'Ver Ciudad', 'your-plugin-textdomain' ),
      'all_items'          => __( 'Ciudades', 'your-plugin-textdomain' ),
      'search_items'       => __( 'Buscar Ciudad', 'your-plugin-textdomain' ),
      'not_found'          => __( 'Ciudad no encontrada.', 'your-plugin-textdomain' ),
      'not_found_in_trash' => __( 'Ciudad no encontrada en la papelera.', 'your-plugin-textdomain' )
    );

    register_post_type( 'ciudades',
      array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'trackbacks', 'page-attributes', 'post-formats'),
        'menu_icon' => 'dashicons-location-alt',
        'menu_position' => 56,
      )
    );
  }
}
add_action( 'init', 'create_post_type_departamentos_ciudades' );
//END

//Creando custom taxonomia de departamentos para las ciudades.
function create_departamento_taxonomies() {
  // Añadimos nueva taxonomía y la hacemos jerárquica (como las categorías por defecto)
  $labels = array(
  'name' => _x( 'Departamentos', 'taxonomy general name' ),
  'singular_name' => _x( 'Departamento', 'taxonomy singular name' ),
  'search_items' =>  __( 'Buscar por Departamento' ),
  'all_items' => __( 'Todos los Departamentos' ),
  'parent_item' => __( 'Departamento padre' ),
  'parent_item_colon' => __( 'Departamento padre:' ),
  'edit_item' => __( 'Editar Departamento' ),
  'update_item' => __( 'Actualizar Departamento' ),
  'add_new_item' => __( 'Añadir nuevo Departamento' ),
  'new_item_name' => __( 'Nombre del nuevo Departamento' ),
);

register_taxonomy( 'departamentos', array( 'ciudades' ), array(
  'hierarchical' => true,
  'labels' => $labels, /* ADVERTENCIA: Aquí es donde se utiliza la variable $labels */
  'show_ui' => true,
  'query_var' => true,
  'rewrite' => array( 'slug' => 'departamentos' ),
));
}
add_action( 'init', 'create_departamento_taxonomies', 0 );

?>