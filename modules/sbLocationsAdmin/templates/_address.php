<table class="sb-location-2-col address">
	<tr>
		<td class="col1">
			
			<div class="a-form-row a-admin-text">
				<?php echo $form['address_line1']->renderLabel(); ?>
				<div class="a-form-field"><?php echo $form['address_line1']->render(); ?></div>
				<?php if($form['address_line1']->hasError()): ?>
				<div class="a-form-error"><?php echo $form['address_line1']->renderError(); ?></div>
				<?php endif; ?>
			</div>
			
			<div class="a-form-row a-admin-text">
				<?php echo $form['address_line2']->renderLabel(); ?>
				<div class="a-form-field"><?php echo $form['address_line2']->render(); ?></div>
				<?php if($form['address_line2']->hasError()): ?>
				<div class="a-form-error"><?php echo $form['address_line2']->renderError(); ?></div>
				<?php endif; ?>
			</div>
			
			<div class="a-form-row a-admin-text">
				<?php echo $form['address_town_city']->renderLabel(); ?>
				<div class="a-form-field"><?php echo $form['address_town_city']->render(); ?></div>
				<?php if($form['address_town_city']->hasError()): ?>
				<div class="a-form-error"><?php echo $form['address_town_city']->renderError(); ?></div>
				<?php endif; ?>
			</div>
			
			<div class="a-form-row a-admin-text">
				<?php echo $form['address_county']->renderLabel(); ?>
				<div class="a-form-field"><?php echo $form['address_county']->render(); ?></div>
				<?php if($form['address_county']->hasError()): ?>
				<div class="a-form-error"><?php echo $form['address_county']->renderError(); ?></div>
				<?php endif; ?>
			</div>
			
			<div class="a-form-row a-admin-text">
				<?php echo $form['address_state']->renderLabel(); ?>
				<div class="a-form-field"><?php echo $form['address_state']->render(); ?></div>
				<?php if($form['address_state']->hasError()): ?>
				<div class="a-form-error"><?php echo $form['address_state']->renderError(); ?></div>
				<?php endif; ?>
			</div>
      
      <div class="a-form-row a-admin-text">
				<?php echo $form['address_postal_code']->renderLabel(); ?>
				<div class="a-form-field"><?php echo $form['address_postal_code']->render(); ?></div>
				<?php if($form['address_postal_code']->hasError()): ?>
				<div class="a-form-error"><?php echo $form['address_postal_code']->renderError(); ?></div>
				<?php endif; ?>
			</div>
			
			<div class="a-form-row a-admin-text">
				<?php echo $form['address_country']->renderLabel(); ?>
				<div class="a-form-field"><?php echo $form['address_country']->render(); ?></div>
				<?php if($form['address_country']->hasError()): ?>
				<div class="a-form-error"><?php echo $form['address_country']->renderError(); ?></div>
				<?php endif; ?>
			</div>
      
      <div class="a-help">If you update the address, don't forget to click 'Find on map' to update map pin.</div>
			
		</td>
		<td class="col2">
			
			<div class="sb-location-coordinates a-form-row">
				
				<a href="#" class="a-btn a-sidebar-button a-save a-show-busy big sb-geocode-lookup">Find on map</a>
				
				<div class="coordinate-fields">
					<div class="coordinate">
						<?php echo $form['geocode_latitude']->renderLabel(); ?>
						<span class="coordinate-value" id="geocode_latitude_value"></span>
						<?php echo $form['geocode_latitude']->render(); ?>
					</div>

					<div class="coordinate">
						<?php echo $form['geocode_longitude']->renderLabel(); ?>
						<span class="coordinate-value" id="geocode_longitude_value"></span>
						<?php echo $form['geocode_longitude']->render(); ?>
					</div>
				</div>
				
			</div>
			
			<div id="sb-location-admin-map"></div>
			
		</td>
	</tr>
</table>

<?php a_js_call('sbLocationsSetupEditMap(?)', sfConfig::get('app_sbLocations_map_system')) ?>