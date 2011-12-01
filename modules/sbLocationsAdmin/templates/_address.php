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
				<?php echo $form['address_country']->renderLabel(); ?>
				<div class="a-form-field"><?php echo $form['address_country']->render(); ?></div>
				<?php if($form['address_country']->hasError()): ?>
				<div class="a-form-error"><?php echo $form['address_country']->renderError(); ?></div>
				<?php endif; ?>
			</div>
			
			<div class="a-form-row a-admin-text">
				<?php echo $form['address_postal_code']->renderLabel(); ?>
				<div class="a-form-field"><?php echo $form['address_postal_code']->render(); ?></div>
				<?php if($form['address_postal_code']->hasError()): ?>
				<div class="a-form-error"><?php echo $form['address_postal_code']->renderError(); ?></div>
				<?php endif; ?>
			</div>
			
		</td>
		<td class="col2">
			
			<div class="a-form-row a-admin-text">
				<?php echo $form['geocode_latitude']->renderLabel(); ?>
				<div class="a-form-field"><?php echo $form['geocode_latitude']->render(); ?></div>
				<?php if($form['geocode_latitude']->hasError()): ?>
				<div class="a-form-error"><?php echo $form['geocode_latitude']->renderError(); ?></div>
				<?php endif; ?>
			</div>
			
			<div class="a-form-row a-admin-text">
				<?php echo $form['geocode_longitude']->renderLabel(); ?>
				<div class="a-form-field"><?php echo $form['geocode_longitude']->render(); ?></div>
				<?php if($form['geocode_longitude']->hasError()): ?>
				<div class="a-form-error"><?php echo $form['geocode_longitude']->renderError(); ?></div>
				<?php endif; ?>
			</div>
			
		</td>
	</tr>
</table>