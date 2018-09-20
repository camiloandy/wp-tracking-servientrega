<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class TrackingServientregaInit
{

  public function __construct() 
  {
    //do action...
    $this->addMenuAdmin();
  }

  private function addMenuAdmin() 
  {
    function TrackingServientregaAddMenu() 
    {
      add_menu_page(
        __( 'Servientrega', 'textdomain' ),
        'Servientrega',
        'manage_options',
        'servientrega',
        'showPageAdminServientrega',
        'dashicons-clipboard',
        56
      );
    }
    add_action( 'admin_menu', 'TrackingServientregaAddMenu' );

    function showPageAdminServientrega() 
    {
      include __DIR__ . '/../templates/admin/front_admin_servientrega.php';
    }

  }
  
}

