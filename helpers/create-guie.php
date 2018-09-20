<?php

function action_woocommerce_payment_complete( $callable, $dataOder) 
{ 
  $objOrder = new WC_Order($dataOder->id);
  $product_items = $objOrder->get_items();
  
  foreach ($product_items as $key => $item) {
    $currentProduct = new WC_Product($item['product_id']);
    $quantity = $item->get_quantity();
  }

  $vendorId = get_post_meta($currentProduct->get_id(), 'vendor_id')[0]; 



  if (!empty($vendorId)) {
    $currentMoney = get_user_meta($vendorId, 'total_money')[0];
    $newComission = ( $objOrder->get_total('edit') * get_option('vendor_comision') ) / 100;
    update_usermeta($vendorId, 'total_money', $currentMoney+$newComission);
  }

  $altoProducto = $currentProduct->get_attribute('alto-producto');
  $largoProducto = $currentProduct->get_attribute('largo-producto');
  $anchoProducto = $currentProduct->get_attribute('ancho-producto');
  $pesoProducto = $currentProduct->get_attribute('peso-producto');
  $pesoVolumetrico = (($altoProducto / 100) * ($anchoProducto / 100) * ($largoProducto / 100)) * 222;
  $precioProducto = $currentProduct->get_price();
  $precioTotal = $precioProducto * $quantity;
  $sobreFlete = ($precioTotal * 1) / 100;

  if ($sobreFlete < 300) 
    $sobreFlete = 300;

  if ($pesoVolumetrico > $pesoProducto) 
    $pesoProducto = $pesoVolumetrico;

  $idComprador = $objOrder->get_customer_id('edit');

  $ciudadDestino = !empty($objOrder->get_shipping_city()) ? $objOrder->get_shipping_city() : $objOrder->get_billing_city();

  $ns = "http://tempuri.org/";

  $headerBody = array(
    'login' => '900646650',
    'pwd' => 'oefB3AtCP3kEqMHXfMV6xQ==',
    'Id_CodFacturacion' => 'SER108183',
    'Nombre_Cargue' =>'TRIQUINET'
  );

  $header = new SOAPHeader($ns, 'AuthHeader', $headerBody);

  $url = "http://web.servientrega.com:8081/GeneracionGuias.asmx?wsdl";

  $ciudadDestino = !empty($objOrder->get_shipping_city()) ? $objOrder->get_shipping_city() : $objOrder->get_billing_city();
  $direccionDestino = !empty($objOrder->get_shipping_address_1()) ? $objOrder->get_shipping_address_1() : $objOrder->get_billing_address_1();
  $estadoDestino = !empty($objOrder->get_shipping_state('edit')) ? $objOrder->get_shipping_state('edit') : $objOrder->get_billing_state('edit'); 

  try {
    $client = new SoapClient($url, [ "trace" => 1 ] );
    $client->__setSoapHeaders($header); 

    //update_post_meta($dataOder->id, 'data_servientrega_guie', $ciudadDestino);

    $parametrosGuia = array(
      'envios' => array(
        'CargueMasivoExternoDTO' => array(
          'objEnvios' => array(
            'EnviosExterno' => array(
              'Num_Guia' => 0,
              'Num_Sobreporte' => 0,
              'Num_Piezas' => $quantity,
              'Des_TipoTrayecto' => 1,
              'Ide_Producto' => 2,
              'Ide_Destinatarios' => '00000000-0000-0000-0000-000000000000',
              'Ide_Manifiesto' => '00000000-0000-0000-0000-000000000000',
              'Des_FormaPago' => 2,
              'Des_MedioTransporte' => 1,
              'Num_PesoTotal' => $pesoProducto,
              'Num_ValorDeclaradoTotal' => $precioTotal,
              'Num_VolumenTotal' => 0,
              'Num_BolsaSeguridad' => 0,
              'Num_Precinto' => 0,
              'Des_TipoDuracionTrayecto' => 1,
              'Des_Telefono' => $objOrder->get_billing_phone('edit'),
              'Des_Ciudad' => $ciudadDestino,
              'Des_Direccion' => $direccionDestino,
              'Nom_Contacto' => 'TRIQUINET',
              'Des_VlrCampoPersonalizado1' => '',
              'Num_ValorLiquidado' => 0,
              'Des_DiceContener' => 'PAQUETE ESTANDAR',
              'Des_TipoGuia' => 0,
              'Num_VlrSobreflete' => 0,
              'Num_VlrFlete' => 0,
              'Num_Descuento' => 0,
              'idePaisOrigen' => 1,
              'idePaisDestino' => 1,
              'Des_IdArchivoOrigen' => 1,
              'Des_DireccionRemitente' => get_user_meta($vendorId, 'user_dir'),
              'Num_PesoFacturado' => 0,
              'Est_CanalMayorista' => false,
              'Num_IdentiRemitente' => get_user_meta($vendorId, 'user_cedula'),
              'Num_TelefonoRemitente' => get_user_meta($vendorId, 'user_cell'),
              'Num_Alto' => $altoProducto,
              'Num_Ancho' => $anchoProducto,
              'Num_Largo' => $largoProducto,
              'Des_DepartamentoDestino' => $estadoDestino,
              'Des_DepartamentoOrigen' => get_user_meta($vendorId, 'user_departamento'),
              'Gen_Cajaporte' => 0,
              'Gen_Sobreporte' => 0,
              'Nom_UnidadEmpaque' => 'GENERICA',
              'Des_UnidadLongitud' => 'cm',
              'Des_UnidadPeso' => 'kg',
              'Num_ValorDeclaradoSobreTotal' => 0,
              'Num_Factura' => $dataOder->id,
              'Des_CorreoElectronico' => $objOrder->get_billing_email('edit'),
              'Num_Recaudo' => 0,
              /*
              'objEnviosUnidadEmpaqueCargue' => array(
                'EnviosUnidadEmpaqueCargue' => array(
                  'Num_Alto' => 3,
                  'Num_Distribuidor' => 0,
                  'Num_Ancho' => 3,
                  'Num_Cantidad' => 1,
                  'Des_DiceContener' => 'TOMA DE MUESTRA',
                  'Des_IdArchivoOrigen' => 1,
                  'Num_Largo' => 3,
                  'Nom_UnidadEmpaque' => 'GENERICA',
                  'Num_Peso' => 3,
                  'Des_UnidadLongitud' => 'cm',
                  'Des_UnidadPeso' => 'kg',
                  'Ide_UnidadEmpaque' => '00000000-0000-0000-0000-000000000000',
                  'Ide_Envio' => '00000000-0000-0000-0000-000000000000',
                  'Num_Volumen' => 3,
                  'Fec_Actualizacion' => array('_' => '', 'nil' => true),
                  'Num_Consecutivo' => 0,
                  'Num_ValorDeclarado' => 50000,
                ),
              ),
              */
              'Est_EnviarCorreo' => false,
            ),
          ),
         ),
      ),
   );

   $result = $client->CargueMasivoExterno($parametrosGuia);
   update_post_meta($dataOder->id, 'data_servientrega_guie', $result);

  } catch ( SoapFault $e ) {
   echo $e->getMessage();
  }
  echo PHP_EOL;

}        
add_action( 'woocommerce_order_status_completed', 'action_woocommerce_payment_complete', 10, 3 ); 