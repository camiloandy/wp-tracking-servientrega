<?php
if ( ! defined( 'ABSPATH' ) ) exit;



function jeroen_sormani_change_city_to_dropdown( $fields ) {
  $city_args = wp_parse_args( array(
    'type' => 'select',
    'options' => array(
      '' => 'Elige una opción...',
    ),
    'input_class' => array(
      'wc-enhanced-select',
    )
  ), $fields['shipping']['shipping_city'] );
  $fields['shipping']['shipping_city'] = $city_args;
  $fields['billing']['billing_city'] = $city_args; // Also change for billing field
  wc_enqueue_js( "
  jQuery( ':input.wc-enhanced-select' ).filter( ':not(.enhanced)' ).each( function() {
    var select2_args = { minimumResultsForSearch: 5 };
    jQuery( this ).select2( select2_args ).addClass( 'enhanced' );
  });" );

  $fields['billing']['billing_cedula'] = array(
        'label'     => __('Cedula', 'woocommerce'),
    'placeholder'   => _x('Cedula', 'placeholder', 'woocommerce'),
    'required'  => true,
    'class'     => array('form-row-wide'),
    'clear'     => true
     );

  $fields['billing']['billing_cedula']['priority'] = 35;

  return $fields;
}


function adding_custom_country_states( $states ) {
  
  $departamentos = get_terms( 'departamentos', array(
    'orderby'    => 'name',
    'order'      => 'ASC',
    'hide_empty' => 0,
  ));

  $states['CO'] = [];

  foreach ($departamentos as $value) {
    $states['CO'][$value->term_id] = $value->name;
  }
    
  return $states;
}


function custom_override_default_address_fields( $address_fields ) {
  $address_fields['state']['label'] = "Departamento";
  $address_fields['state']['default'] = "39";

  $address_fields['city']['priority'] = 80;

  return $address_fields;
}

if (! function_exists('addSelecFields')) {
  function addSelecFields() 
  {
    if (! get_option('use_list_city_states'))
      return; 
    
    add_filter( 'woocommerce_checkout_fields', 'jeroen_sormani_change_city_to_dropdown' );
    add_filter( 'woocommerce_states', 'adding_custom_country_states' );
    add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );
  }
}
addSelecFields();

function bbloomer_move_checkout_email_field( $address_fields ) {
    $address_fields['billing_state']['priority'] = 6;
    return $address_fields;
}
 
add_filter( 'woocommerce_billing_fields', 'bbloomer_move_checkout_email_field', 10, 1 );


add_action( 'woocommerce_admin_order_data_after_shipping_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );

function my_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Cedula').':</strong> ' . get_post_meta( $order->get_id(), '_billing_cedula', true ) . '</p>';
    echo '<p><strong>'.__('N° Guía').':</strong> ' . get_post_meta( $order->get_id(), 'data_servientrega_guie', true ) . '</p>';
}