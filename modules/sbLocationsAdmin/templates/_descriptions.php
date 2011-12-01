<table class="sb-location-2-col">
	<tr>
		<td class="col1">
			<?php echo $form['description']->renderLabel(); ?>
			<?php echo $form['description']->render(); ?>
			<?php if($form['description']->hasError()): ?>
				<?php echo $form['description']->renderError(); ?>
			<?php endif; ?>
		</td>
		<td class="col2">
			<?php echo $form['map_description']->renderLabel(); ?>
			<?php echo $form['map_description']->render(); ?>
			<?php if($form['description']->hasError()): ?>
				<?php echo $form['description']->renderError(); ?>
			<?php endif; ?>
		</td>
	</tr>
</table>