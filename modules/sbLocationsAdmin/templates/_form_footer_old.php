<?php use_helper('a') ?>
<?php a_js_call('aMultipleSelectAll(?)', array('choose-one' => a_('Select to Add'))) ?>

<fieldset id="a-fieldset-photos">
	<?php $location = $form->getObject(); ?>
<?php include_partial('sbLocationsAdmin/photo_header', array('location' => $location)); ?>
<?php include_component('sbLocations', 'LocationSlideShow', array('location' => $location)); ?>
</fieldset>