<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ) return;

if (!function_exists('wc_contra_entrega_paiment_gateway_init')) {
  function wc_contra_entrega_paiment_gateway_init () 
  {
    class ContraEntregaPaimentGateway extends WC_Payment_Gateway 
    {
      public function __construct() 
      {
        $this->id                   = 'wc-gateway-contra-entrega';
        $this->icon                 = get_site_url() . '/wp-content/plugins/triquinet-personalized/assets/images/servientrega.png';
        $this->has_fields           = false;
        $this->method_title         = __( 'Contra Entrega - Servientrega S. A.', 'wc-gateway-contra-entrega' );
        $this->method_description   = __( 'Permite pagos a traves de su saldo.', 'wc-gateway-contra-entrega' );

        $this->init_form_fields();
        $this->init_settings();

        $this->title        = $this->get_option( 'title' );
        $this->description  = $this->get_option( 'description' );
        $this->instructions = $this->get_option( 'instructions', $this->description );

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
      
        // Customer Emails
        add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
      }

      public function init_form_fields() 
      {
        $this->form_fields = apply_filters( 'wc_contra_entrega_form_fields', array(
          
        'enabled' => array(
            'title'   => __( 'Enable/Disable', 'wc-gateway-contra-entrega' ),
            'type'    => 'checkbox',
            'label'   => __( 'Habilitar Pago contra entrega Servientrega S. A.', 'wc-gateway-contra-entrega' ),
            'default' => 'yes'
        ),

        'title' => array(
            'title'       => __( 'Titulo', 'wc-gateway-contra-entrega' ),
            'type'        => 'text',
            'description' => __( 'Forma de pago a traves de contra entrega de Servientrega S. A..', 'wc-gateway-contra-entrega' ),
            'default'     => __( 'Contra Entrega Servientrega S. A.', 'wc-gateway-contra-entrega' ),
            'desc_tip'    => true,
        ),

        'description' => array(
            'title'       => __( 'Descripción', 'wc-gateway-contra-entrega' ),
            'type'        => 'textarea',
            'description' => __( 'Con este medio de pago puedes comprar productos en la tienda y pagar contra entrega con los servicios de Servientrega S. A..', 'wc-gateway-contra-entrega' ),
            'default'     => __( 'Con este medio de pago puedes comprar productos en la tienda y pagar contra entrega con los servicios de Servientrega S. A..', 'wc-gateway-contra-entrega' ),
            'desc_tip'    => true,
        ),

        'instructions' => array(
            'title'       => __( 'Instrucciones', 'wc-gateway-contra-entrega' ),
            'type'        => 'textarea',
            'description' => __( 'Con este medio de pago puedes comprar productos en la tienda y pagar contra entrega con los servicios de Servientrega S. A..', 'wc-gateway-contra-entrega' ),
            'default'     => '',
            'desc_tip'    => true,
        ),
        ));
      }

      public function process_payment($order_id) 
      {
        $order = wc_get_order($order_id);

        $order->update_status('completed', __('Awaiting saldo payment'), 'wc-gateway-contra-entrega');
        $order->reduce_order_stock();

        WC()->cart->empty_cart();
            
        // Return thankyou redirect
        return array(
            'result'    => 'success',
            'redirect'  => $this->get_return_url( $order )
        );
      }

      public function thankyou_page()
      {
        if ( $this->instructions) {
          echo wpautop( wptexturize( $this->instructions ) );
        }
      }

      public function email_instructions($order, $sent_to_admin, $plain_text = false) 
      {
        if ( $this->instructions && ! $sent_to_admin && 'contra_entrega' === $order->payment_method && $order->has_status( 'on-hold' ) ) {
          echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
        }
      }
    }
  }  
}
add_action('plugins_loaded', 'wc_contra_entrega_paiment_gateway_init', 11);

if (!function_exists('wc_contra_entrega_add_to_gateways')) {
  function wc_contra_entrega_add_to_gateways($gateways) 
  {
    $gateways[] = 'ContraEntregaPaimentGateway';
    return $gateways;
  }
}
add_filter('woocommerce_payment_gateways', 'wc_contra_entrega_add_to_gateways');

if (!function_exists('wc_contra_entrega_gateway_plugin_links')) {
  function wc_contra_entrega_gateway_plugin_links( $links ) 
  {
    $plugin_links = array(
      '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=contra_entrega_gateway' ) . '">' . __( 'Configure', 'wc-gateway-contra-entrega' ) . '</a>'
    );
    return array_merge( $plugin_links, $links );
  }
  add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wc_contra_entrega_gateway_plugin_links' );
}

//Pruebas para mostrar datos del usuario en el checkout


add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

function woocommerce_checkout_payment() 
{
  if ( WC()->cart->needs_payment() ) {
    $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
    WC()->payment_gateways()->set_current_gateway( $available_gateways );
  } else {
    $available_gateways = array();
  }

  $order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) );

  /*
  wc_get_template( 'checkout/payment.php', array(
    'checkout'           => WC()->checkout(),
    'available_gateways' => $available_gateways,
    'order_button_text'  => apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) ),
  ));
  */

  if ( ! is_ajax() ) {
    do_action( 'woocommerce_review_order_before_payment' );
  }
  ?>
  <div id="payment" class="woocommerce-checkout-payment">
    <?php if ( WC()->cart->needs_payment() ) : ?>
      <ul class="wc_payment_methods payment_methods methods" id="payment_methods_show">
        <?php
        if ( ! empty( $available_gateways ) ) {
          foreach ( $available_gateways as $gateway ) {
            ?>
              

<li class="wc_payment_method payment_method_<?php echo $gateway->id; ?>">
  <input id="payment_method_<?php echo $gateway->id; ?>" type="radio" class="input-radio payment_method_verify" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

  <label for="payment_method_<?php echo $gateway->id; ?>">
    <?php echo $gateway->get_title(); ?> <?php echo $gateway->get_icon(); ?>
  </label>
  <?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
    <div class="payment_box payment_method_<?php echo $gateway->id; ?>" <?php if ( ! $gateway->chosen ) : ?>style="display:none;"<?php endif; ?>>
      <?php $gateway->payment_fields(); 
      //Agregando datos de saldo del usuario
      if($gateway->id == 'contra_entrega_gateway') {
        $currentID = get_current_user_id();  
        $userMoneyAvailable = get_usermeta($currentID, 'user_money_available');

        global $woocommerce;
        $totalMount = $woocommerce->cart->get_total('edit');

        if ( $userMoneyAvailable < $totalMount ) {
          echo "<p class='show-contra-entrega-user' data-canpay='false'>Saldo Disponible: <span style='color:red'>$ ".number_format($userMoneyAvailable, 0, '','.')."</span></p><p class='error-mensaje'>Tu saldo no es suficiente para pagar este producto.</p>";
        } else {
          echo "<p class='show-contra_entrega-user' data-canpay='true'>Saldo Disponible: <span style='color:green'>$ ".number_format($userMoneyAvailable, 0, '','.')."</span></p>";
        }


      }
      ?>

    </div>
  <?php endif; ?>
</li>

            <?php
          }
        } else {
          echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
        }
        ?>
<script type="text/javascript">

  function buttonPlaceOrderEnabledDisabled(elemento) {
    var canPay = jQuery('p.show-contra-entrega-user').data('canpay');
    if (jQuery(elemento).prop('id') == 'payment_method_contra_entrega_gateway' && jQuery(elemento).prop('checked')) { 
      if (canPay) {
        jQuery('#place_order').prop('disabled', false);
      } else {
        jQuery('#place_order').prop('disabled', true);
      }
    } else {
      jQuery('#place_order').prop('disabled', false);
    }
  }

  buttonPlaceOrderEnabledDisabled(jQuery('#payment_method_contra_entrega_gateway'));

  jQuery('input[name=payment_method]').change(function(){
    buttonPlaceOrderEnabledDisabled(this)
  });
</script>
      </ul>
    <?php endif; ?>
    <div class="form-row place-order">
      <noscript>
        <?php esc_html_e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?>
        <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
      </noscript>

      <?php wc_get_template( 'checkout/terms.php' ); ?>

      <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

      <?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

      <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

      <?php wp_nonce_field( 'woocommerce-process_checkout' ); ?>
    </div>
  </div>
  <?php
  if ( ! is_ajax() ) {
    do_action( 'woocommerce_review_order_after_payment' );
  }

}