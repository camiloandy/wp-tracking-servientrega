<?php
if (! defined('WPINC') )
  die;

/*
* Check if WooCommerce is active
*/
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
  function servientregaShipingMethod() 
  {
    if (! class_exists('ServientregaShipingMethod')) {
      class ServientregaShipingMethod extends WC_Shipping_Method 
      {
        /**
        * Constructor for your shipping class
        *
        * @access public
        * @return void
        */
        public function __construct() 
        {
          $this->id = 'servientrega';
          $this->method_title = __('Servientrega', 'servientrega');
          $this->method_description = __('Metodo de envio a traves de los servicios de Servientrega S.A.', 'servientrega');
          
          $this->availability = 'including';
          $this->countries = array('CO');
          
          $this->init();
          $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : false;
          $this->title = isset($this->settings['title']) ? $this->settings['title'] : 'yes';
        }

        /**
        * Init your settings
        *
        * @access public
        * @return void
        */
        public function init() 
        {
          $this->init_form_fields();
          $this->init_settings();

          add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));  
        }

        /**
        * Define settings field for this shipping
        * @return void 
        */
        function init_form_fields()
        {
          $this->form_fields = array(
            'enabled' => array(
              'title'       => __('Enabled', 'servientrega'),
              'type'        => 'checkbox',
              'description' => __('Enabled this shipping', 'servientrega'),
              'default'     => false,
            ),
            'title' => array(
              'title'       => __('Servientrega', 'servientega'),
              'type'        => 'text',
              'description' => __('Title for display on site', 'servientrega'),
              'default'     => __('Servientrega', 'servientrega'),
            ),
            'weight' => array(
              'title'       => __('Weight (kg)', 'servientrega'),
              'type'        => 'number',
              'description' => __('Maximum allowed weight', 'servientrega'),
              'default'     => 100,
            ),
          );
        }

        /**
        * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
        *
        * @access public
        * @param mixed $package
        * @return void
        */
        public function calculate_shipping($package = array()) 
        {
          foreach ( $package['contents'] as $item_id => $values ) { 
            $product = $values['data']; 
            //$weight = $weight + $product->get_weight() * $values['quantity']; 
            $quantity = $values['quantity'];
          }

          $altoProducto = $product->get_attribute('alto-producto');
          $largoProducto = $product->get_attribute('largo-producto');
          $anchoProducto = $product->get_attribute('ancho-producto');
          $pesoProducto = $product->get_attribute('peso-producto');
          $pesoVolumetrico = (($altoProducto / 100) * ($anchoProducto / 100) * ($largoProducto / 100)) * 222;
          $precioProducto = $product->get_price();
          $precioTotal = $precioProducto * $quantity;
          

          if ($pesoVolumetrico > $pesoProducto) 
            $pesoProducto = $pesoVolumetrico;

          global $woocommerce;
          $comprador = $woocommerce->cart->get_customer();
          
          $departamentoComprador = $package['destination']['state'];
          $departamentoComprador = get_term_by('id', $departamentoComprador, 'departamentos')->name;
          $departamentoComprador = change_accent_mark($departamentoComprador);
          $departamentoComprador = strtoupper($departamentoComprador);
          $ciudadComprador = strtoupper($package['destination']['city']);
          $ciudadComprador = change_accent_mark($ciudadComprador);

          $productData = get_postdata($product->get_id());
          
          $departamentoVendedor = get_option('origen_departamento');
          $departamentoVendedor = get_term_by('id', $departamentoVendedor, 'departamentos')->name;
          $departamentoVendedor = change_accent_mark($departamentoVendedor);
          $departamentoVendedor = strtoupper($departamentoVendedor);
          
          $ciudadVendedor = strtoupper(get_option('origen_ciudad'));
          $ciudadVendedor = change_accent_mark($ciudadVendedor);

          $origen = $ciudadVendedor . '-' . $departamentoVendedor;
          $destino = $ciudadComprador . '-' . $departamentoComprador;
          $firstLetter = substr($origen, 0,1);
          $firstLetter = strtolower($firstLetter);

          $chosen_payment = WC()->session->get( 'chosen_payment_method' );

          $zona = 'NONE';
          $sobreFlete = ($precioTotal * 1) / 100;
          if ($sobreFlete < 300) 
            $sobreFlete = 300;

          $archivo = fopen("C:\\xampp71\htdocs\arbocol2\wp-content\plugins\wp-tracking-servientrega\csv-files\servientrega_dos_$firstLetter.csv", "r");
          while (($datos = fgetcsv($archivo)) == true) {
            $data = explode('|', $datos[0]);  
            if ($origen === $data[0] and $destino === $data[1]) {
              $zona = $data[2];
              break;
            }
          }
          
          fclose($archivo);          

          $costShipping = 0;

          if($zona === 'NACIONAL') {
            if ($pesoProducto <= 4)
              $costShipping = 9900;

            if ($pesoProducto > 4 and $pesoProducto <= 13)
              $costShipping = 16300;

            if ($pesoProducto > 13 and $pesoProducto <= 31)
              $costShipping = 26500;

            if ($pesoProducto > 31 and $pesoProducto <= 51)
              $costShipping = 34000;

            if ($pesoProducto > 51 and $pesoProducto <= 80)
              $costShipping = 40500;

            if ($pesoProducto > 80) {
              $pesoExtra = $pesoProducto - 80;
              $costShipping = 40500 + ($pesoExtra*1500);
            }
          }

          if($zona === 'ZONAL') {
            if ($pesoProducto <= 4)
              $costShipping = 6800;

            if ($pesoProducto > 4 and $pesoProducto <= 13)
              $costShipping = 11500;

            if ($pesoProducto > 13 and $pesoProducto <= 31)
              $costShipping = 18400;

            if ($pesoProducto > 31 and $pesoProducto <= 51)
              $costShipping = 21400;

            if ($pesoProducto > 51 and $pesoProducto <= 80)
              $costShipping = 25800;

            if ($pesoProducto > 80) {
              $pesoExtra = $pesoProducto - 80;
              $costShipping = 25800 + ($pesoExtra*1500);
            }
          }

          if($zona === 'URBANO') {
            if ($pesoProducto <= 4)
              $costShipping = 5800;

            if ($pesoProducto > 4 and $pesoProducto <= 13)
              $costShipping = 9800;

            if ($pesoProducto > 13 and $pesoProducto <= 31)
              $costShipping = 15700;

            if ($pesoProducto > 31 and $pesoProducto <= 51)
              $costShipping = 20100;

            if ($pesoProducto > 51 and $pesoProducto <= 80)
              $costShipping = 23800;

            if ($pesoProducto > 80) {
              $pesoExtra = $pesoProducto - 80;
              $costShipping = 23800 + ($pesoExtra*1500);
            }
          }

          if($zona === 'ESPECIAL') {
            if ($pesoProducto <= 4)
              $costShipping = 19000;

            if ($pesoProducto > 4 and $pesoProducto <= 13)
              $costShipping = 31600;

            if ($pesoProducto > 13 and $pesoProducto <= 31)
              $costShipping = 76700;

            if ($pesoProducto > 31 and $pesoProducto <= 51)
              $costShipping = 127800;

            if ($pesoProducto > 51 and $pesoProducto <= 80)
              $costShipping = 130600;

            if ($pesoProducto > 80) {
              $pesoExtra = $pesoProducto - 80;
              $costShipping = 130600 + ($pesoExtra*3000);
            }
          }

          $finalCost = ($costShipping + $sobreFlete);

          if($zona === 'NONE')
            $finalCost = 1;

          $rate = array(
            'id' => $this->id,
            'label' => $this->title,
            'cost' => $finalCost,
            'calc_tax' => 'per_item'
          );
 
          // Register the rate
          $this->add_rate( $rate );
        }
      }
    }
  }

  add_action('woocommerce_shipping_init', 'servientregaShipingMethod');
}

function addServientregaShippingMethod($methods) 
{
  $methods[] = 'ServientregaShipingMethod';
  return $methods;
}
add_filter('woocommerce_shipping_methods', 'addServientregaShippingMethod');

function servientregaValidateOrder( $posted )   
{
  $packages = WC()->shipping->get_packages(); 
  $newRateShipping = WC()->cart->calculate_shipping();
  
  $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );

  if (is_array( $chosen_methods ) && in_array( 'servientrega', $chosen_methods )) {             
    foreach ($packages as $i => $package) {
      if ($chosen_methods[ $i ] != "servientrega")                           
        continue;                             
                 
      $ServientregaShipingMethod = new ServientregaShipingMethod();
      $weightLimit = (int) $ServientregaShipingMethod->settings['weight'];
      $weight = 0;

      /*
      if ($_product->get_weight()) {
        foreach ($package['contents'] as $item_id => $values) { 
          $_product = $values['data']; 
          $weight = $weight + $_product->get_weight() * $values['quantity']; 
        }  
      }
      */ 
      
      $weight = wc_get_weight($weight, 'kg');
                
      if ($weight > $weightLimit) { 
        $message = sprintf( __( 'Sorry, %d kg exceeds the maximum weight of %d kg for %s', 'servientrega' ), $weight, $weightLimit, $ServientregaShipingMethod->title );         
        $messageType = "error";
        if( ! wc_has_notice( $message, $messageType ) ) 
          wc_add_notice( $message, $messageType );
      }

      $amountOfShipping = WC()->cart->get_shipping_total();

      if ($amountOfShipping <= 1) { 
        $message = sprintf( __( 'Lo siento, no tenemos envío para tu dirección, verifica y coloca una dirección valida.' ), $weight, $weightLimit, $ServientregaShipingMethod->title );         
        $messageType = "error";
        if( ! wc_has_notice( $message, $messageType ) ) 
          wc_add_notice( $message, $messageType );
      }

    }       
  } 

}
 
add_action( 'woocommerce_review_order_before_cart_contents', 'servientregaValidateOrder' , 10 );
add_action( 'woocommerce_after_checkout_validation', 'servientregaValidateOrder' , 10 );

add_action( 'woocommerce_checkout_update_order_review', 'woocommerce_checkout_update_order_review' );
function woocommerce_checkout_update_order_review( $post_data ){
  $packages = WC()->shipping->get_packages(); 
  $calculate = WC()->shipping->init();

  
}
?>
