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
                  echo "<option value='". $value->term_id ."' >". $value->name ."</option>";                      
                }
              ?>
            </select>
          <small id="textHelpUserDepartamento" class="form-text text-muted">Seleccione, por ejemplo: Cundinamarca.</small>

          <label for="origen-ciudad">Ciudad </label>
          <select id="origen-ciudad" name="origen-ciudad" class="custom-select" aria-describedby="textHelpUserCiudad"  disabled>
              <option value="" >Seleccionar</option>
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
</div>
