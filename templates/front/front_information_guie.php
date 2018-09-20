<?php
    $orderId = $_GET['order'];
    $guieId = $_GET['guie'];

    $url = "http://sismilenio.servientrega.com.co/wsrastreoenvios/wsrastreoenvios.asmx?WSDL";
    $client = new SoapClient($url);
    $resultEstadoGuia = $client->EstadoGuia(array("ID_Cliente" => '900646650', "guia" => $guieId)); 
    $objetoxmlEstadoGuia = simplexml_load_string($resultEstadoGuia->EstadoGuiaResult->any);
    $novedad = $objetoxmlEstadoGuia->NewDataSet->EstadosGuias->Novedad;

    $resultConsultarInfoGuias = $client->ConsultarInfoGuias(array("NumerosGuias" => $guieId));
    $objConsultarInfoGuias = simplexml_load_string($resultConsultarInfoGuias->ConsultarInfoGuiasResult->any);
    $stateGuie = $objConsultarInfoGuias->GuiasDTO->IdEstAct;
    $stateGuieName = $objConsultarInfoGuias->GuiasDTO->EstAct;
    $movimientos = $objConsultarInfoGuias->GuiasDTO->Movimientos;

    $hasFechEst = ! stripos($objConsultarInfoGuias->GuiasDTO->FecEst, '0001');
?>

<div class="container information-guie">
    <div class="row">
        <div class="col-12">
            <h2>Información de guía.</h2>
            <table class="tabla-pedidos">
        <tbody>
    <?php
    //Query orders

    $argsOrders = array(
      'post__in' => array( $orderId ),
      'post_type' => 'shop_order',
      'post_status' => 'any',
      'meta_query' => 
        array(
          'relation' => 'AND',
          array(
            'key' => '_customer_user',
            'value' => get_current_user_id(),
            'compare' => '=',
          ),
          array(
            'key' => '_date_completed',
            'value' => '',
            'compare' => '!=',
          ),
        ),
    );

    $queryOrders = new WP_Query($argsOrders);

    if ( $queryOrders->have_posts()) {
      while ($queryOrders->have_posts()) {
        $queryOrders->the_post();
        $postID = get_the_ID();

        //print shop orders
        $objOrder = new WC_Order($postID);
        //Get the date format
        $dateCompleted = get_post_meta($postID)['_date_completed'][0];

        $idOrderItem = 0;
        foreach ($objOrder->get_items() as $key => $value)
          $idOrderItem = $key;
        
        $objOrderItem = new WC_Order_Item_Product($idOrderItem);
        $productOrderId = $objOrderItem->get_product_id();
        $objProduct = new WC_Product($productOrderId);

      ?>

            <tr>
                <th>N° Pedido</th>
                <td><span>#</span><?php echo $objOrder->get_order_number(); ?></td>
            </tr>
            <tr>
                <th>Articulo</th>
                <td class="cell-product-info">
                    <?php echo $objProduct->get_image(); ?>
                    <a href="<?php echo $objProduct->get_permalink(); ?>" class="title-product-order-list"><?php echo $objProduct->get_name(); ?></a>
                </td>
            </tr>
            <tr>
                <th>Fecha</th> 
                <td><?php echo date('m/d/Y', $dateCompleted); ?></td>
            </tr>
            <tr>
                <th>Total</th>
                <td><?php echo $objOrder->get_formatted_order_total(); ?></td>
            </tr>
            <tr>
                <th>Guia</th>
                <td><?php echo $guieId; ?></td>
            </tr>
            <?php 
            if (! empty($novedad)) {
            ?>
            <tr>
                <th>Novedad</th>
                <td><?=$novedad?></td>
            </tr>
            <?
            }
            if ($stateGuie > 0 ) {
            ?>
            <tr>
                <th>Estado de Envío</th>
                <td><?=$stateGuieName?></td>
            </tr>
            <tr>
                <th>Fecha Envío</th>
                <td><?=$objConsultarInfoGuias->GuiasDTO->FecEnv?></td>
            </tr>
            <tr>
                <th>Ciduad Remitente</th>
                <td><?=$objConsultarInfoGuias->GuiasDTO->CiuRem?></td>
            </tr>
            <tr>
                <th>Nombre Remitente</th>
                <td><?=$objConsultarInfoGuias->GuiasDTO->NomRem?></td>
            </tr>
            <tr>
                <th>Dirección Remitente</th>
                <td><?=$objConsultarInfoGuias->GuiasDTO->DirRem?></td>
            </tr>
            <tr>
                <th>Ciudad Destino</th>
                <td><?=$objConsultarInfoGuias->GuiasDTO->CiuDes?></td>
            </tr>
            <tr>
                <th>Nombre Destino</th>
                <td><?=$objConsultarInfoGuias->GuiasDTO->NomDes?></td>
            </tr>
            <tr>
                <th>Dirección Destino</th>
                <td><?=$objConsultarInfoGuias->GuiasDTO->DirDes?></td>
            </tr>
            <?php
            }
            if ($hasFechEst) {
            ?>
            <tr>
                <th>Fecha Estimada</th>
                <td><?=$objConsultarInfoGuias->GuiasDTO->FecEst?></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
      <?php
      }
      }

      ?>

      </tbody>
    </table>

    
    <?php
        if (count($movimientos[0])) 
            echo '<h3 style="font-size:18px; text-align:center">MOVIMIENTOS</h3>';

        foreach($movimientos as $movimiento) {
            foreach($movimiento as $key => $value) {
                echo "<table class='table-movimiento'><thead><td colspan='2'>Movimiento</td></thead>";
                echo "<tbody>";
                foreach($value as $key => $element) {
                    if ($key != 'IdProc' and 
                        $key != 'IdConc' and 
                        $key != 'NomConc' and 
                        $key != 'OriMov' and 
                        $key != 'IdViewCliente' and
                        $key != 'TipoMov' and
                        $key != 'DesTipoMov') {
                        echo "<tr><th>$key</th>";
                        echo "<td>$element</td>";
                        echo "</tr>";
                    }
                    
                }
                echo "</tbody></table>";
            ?>
            <?php
            }
            
        }
    ?>

        </div>
    </div>
</div>