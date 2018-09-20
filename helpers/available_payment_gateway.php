<?php
if (! defined('WPINC') )
  die;
function my_custom_available_payment_gateways( $gateways ) {

    global $woocommerce;
    
	$chosen_shipping_rates = WC()->session->get( 'chosen_shipping_methods' );
	if ( in_array( 'servientrega', $chosen_shipping_rates ) && isset($gateways['wc-gateway-contra-entrega'] ) ) :
        unset( $gateways['wc-gateway-contra-entrega'] );
    endif;
    
    if ( in_array( 'contraentregaservientrega', $chosen_shipping_rates ) && isset($gateways['payulatam']) ):
        unset( $gateways['payulatam'] );
    endif;
    
    return $gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'my_custom_available_payment_gateways' );

function action_woocommerce_checkout_update_order_review() { 
    $chosen_payment = WC()->session->get( 'chosen_payment_method' );
    $chosenShipping = WC()->session->get( 'chosen_shipping_methods' );

    if ( $chosenShipping[0] == 'servientrega') {
        WC()->session->set( 'chosen_payment_method', 'payulatam' );
    } else if ($chosenShipping[0] == 'contraentregaservientrega') {
        WC()->session->set( 'chosen_payment_method', 'wc-gateway-contra-entrega' );
    }     
 };
 add_action( 'woocommerce_before_calculate_totals', 'action_woocommerce_checkout_update_order_review', 10, 2 );  

?>