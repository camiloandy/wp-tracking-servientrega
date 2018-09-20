<?php
/*
Plugin Name: WP Traking Servientrega.
Plugin URI: http://www.ovidiojosearteaga.com/wptrackingservientrega
Description: Plugin para rastreo y manejo de envíos a través de la empresa servientrega
Version: 1.0
Author: Ovidio Arteaga
Author URI: http://www.ovidiojosearteaga.com
License: GPL2
*/

//if ( ! defined( 'ABSPATH' ) ) exit;

//define( 'NAME_PLUGIN', 'wp-traking-servientrega' );

include __DIR__ . '/includes/TrackingServientregaInit.php';
include __DIR__ . '/helpers/register-post-type.php';
include __DIR__ . '/helpers/shortcodes.php';
include __DIR__ . '/helpers/servientrega-shiping-method.php';
include __DIR__ . '/helpers/contra-entrega-servientrega-shiping-method.php';
include __DIR__ . '/helpers/change_accent_mark.php';
include __DIR__ . '/helpers/create-guie.php';
include __DIR__ . '/helpers/available_payment_gateway.php';
include __DIR__ . '/helpers/cpt-departamentos-ciudades.php';
include __DIR__ . '/includes/ShowInormationGuie.php';


//Agregando la hoja de estilos css principal
if (!function_exists('WPTrackingServientregaCss')) {
  function WPTrackingServientregaCss() 
  {
    wp_register_style( 'wptrackingservientregacss', plugin_dir_url( __DIR__ ) . 'wp-tracking-servientrega/assets/css/main-styles.css', '', 1 );
    wp_enqueue_style( 'wptrackingservientregacss' );
  }
}
add_action( 'wp_print_styles', 'WPTrackingServientregaCss' );
//END

//Agregando Javascripts archivo principal
if ( ! function_exists( 'WPTrackingServientregaJs') ) {
  function WPTrackingServientregaJs() {
      wp_register_script( 'wptrackingservientregamainjs', plugin_dir_url( __DIR__ ) . 'wp-tracking-servientrega/assets/js/main.js', '', '1', true );
      wp_enqueue_script( 'wptrackingservientregamainjs' );
      wp_localize_script( 'wptrackingservientregamainjs', 'wtse_mainjs_vars', ['ajaxurl' => admin_url( 'admin-ajax.php' ) ] );
  }
}
add_action( 'wp_enqueue_scripts', 'WPTrackingServientregaJs' );
//END


//Init core plugin
$pluginInit = new TrackingServientregaInit();


