  <div class="container">
  <div class="row">
    <h2 class="col-12">Modulo de manejo de envios Servientrega</h2>
  </div>
  <p></p>
  <div class="row">
  	<div class="col-12">
  		<h3>Configuración de ciudad y departamento de origen.</h3>
  		<form id="origen-ciudad-departamento">
  			<label for="origen-departamento">Departamento</label>
          <select id="origen-departamento" name="origen-departamento" class="custom-select" aria-describedby="textHelpUserDepartamento" >
              <option value="" >Seleccionar</option>
              <?php
                $departamentos = get_terms( 'departamentos', array(
                  'orderby'    => 'name',
                  'order'      => 'ASC',
                  'hide_empty' => 0,
                ));

                foreach ($departamentos as $value) {
                  if ($value->term_id == get_option('origen_departamento'))
                    echo "<option selected value='". $value->term_id ."' >". $value->name ."</option>";                      
                      
                  echo "<option value='". $value->term_id ."' >". $value->name ."</option>";                      
                }
              ?>
            </select>
          <small id="textHelpUserDepartamento" class="form-text text-muted">Seleccione, por ejemplo: Cundinamarca.</small>

          <label for="origen-ciudad">Ciudad </label>
          <select id="origen-ciudad" name="origen-ciudad" class="custom-select" aria-describedby="textHelpUserCiudad" >
              <option value="" >Seleccionar</option>
              <?php
                $idDepartamento = get_option('origen_departamento');
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
                    $ciudadSeleted = get_option('origen_ciudad');

                    if(get_the_title() == $ciudadSeleted)
                      echo "<option value='". get_the_title() ."' selected>". get_the_title() ."</option>";                    

                    echo "<option value='". get_the_title() ."' >". get_the_title() ."</option>";                    
                  }
                }

              ?>
          </select>
          <small id="textHelpUserCiudad" class="form-text text-muted">Seleccione, por ejemplo: Bogota.</small>
          <img class="loading-cities-admin" src="<?=get_site_url()?>/wp-content/plugins/wp-tracking-servientrega/assets/images/loading-admin.gif" alt="loading cities" width="30">

          <input type="submit" value="Guardar" id="save-origen-ciudad-departamento" name="save-origen-ciudad-departamento" class="button">

          <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/loading-admin.gif" class="load-img" width="32">
		      <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/success.png" class="success-img">
		      <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/error.png" class="error-img">

		      <p><span class="messagge-template-error">* Debes seleccionar una ciudad y un departamento.</span></p>
  		</form>
  	</div>
  </div>

  <div class="row">
    <div class="col-12">
      <h3>Usar lista para ciudades y departamentos</h3>
      <form id="lista-ciudad-departamento">

        <?php $useList = get_option('use_list_city_states'); ?>
        <label>SÍ <input type="radio" name="lista-ciudades-departamentos" id="lista-ciudades-departamento-si"
          <?= $useList == 1 ? 'checked' : ''?> ></label>
        <label>NO <input type="radio" name="lista-ciudades-departamentos" id="lista-ciudades-departamento-no" 
          <?= $useList == 1 ? '' : 'checked'?> ></label>

        <input type="submit" value="Guardar" id="save-lista-ciudad-departamento" name="save-lista-ciudad-departamento" class="button">

        <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/loading-admin.gif" class="load-img" width="32">
        <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/success.png" class="success-img">
        <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/error.png" class="error-img">

      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <h3>Datos de acceso Servientrega</h3>
      <form id="datos-acceso-servientrega">
        
        <label for="login-servitentrega">Login</label>
        <input type="text" name="login-servitentrega" id="login-servitentrega" value="<?=get_option('login_servientrega')?>">

        <label for="password-servientrega">Password</label>
        <input type="password" id="password-servientrega" name="password-servientrega" value="<?=get_option('password_servientrega')?>">
        <span>ver password</span>

        <label for="codigo-facturacion-servientrega">Código de facturación</label>
        <input type="text" name="codigo-facturacion-servientrega" id="codigo-facturacion-servientrega" value="<?=get_option('codigo_facturacion_servientrega')?>">

        <label for="nombre-cargue-servientrega">Nombre cargue</label>
        <input type="text" name="nombre-cargue-servientrega" id="nombre-cargue-servientrega" value="<?=get_option('nombre_cargue_servientrega')?>">
        
        <input type="submit" value="Guardar" id="save-datos-acceso-servientrega" name="save-origen-ciudad-departamento" class="button">

        <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/loading-admin.gif" class="load-img" width="32">
        <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/success.png" class="success-img">
        <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/error.png" class="error-img">

        <p><span class="messagge-template-error">* Debes seleccionar una ciudad y un departamento.</span></p>
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <h3>Datos de producto por defecto.</h3>
      <form id="datos-producto-servientrega">
        
        <label for="lenght-product-servitentrega">Longitud</label>
        <input type="number" name="lenght-product-servitentrega" id="lenght-product-servitentrega" value="<?=get_option('lenght_servientrega')?>" placeholder="cm" min="1" step="any">

        <label for="height-product-servitentrega">Alto</label>
        <input type="number" name="height-product-servitentrega" id="height-product-servitentrega" value="<?=get_option('height_servientrega')?>" placeholder="cm" min="1" step="any">

        <label for="width-product-servitentrega">Ancho</label>
        <input type="number" name="width-product-servitentrega" id="width-product-servitentrega" value="<?=get_option('width_servientrega')?>" placeholder="cm" min="1" step="any">

        <label for="weight-product-servitentrega">Peso</label>
        <input type="number" name="weight-product-servitentrega" id="weight-product-servitentrega" value="<?=get_option('weight_servientrega')?>" placeholder="cm" min="0.1" step="any">

        <input type="submit" value="Guardar" id="save-datos-producto-servientrega" name="save-datos-producto-servientrega" class="button">

        <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/loading-admin.gif" class="load-img" width="32">
        <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/success.png" class="success-img">
        <img src="<?php echo get_site_url() ?>/wp-content/plugins/wp-tracking-servientrega/assets/images/error.png" class="error-img">

      </form>
    </div>
  </div>
</div>
