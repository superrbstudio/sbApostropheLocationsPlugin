<li class="a-admin-action-new">
    <?php echo link_to(a_('<span class="icon"></span>New Location', array(), 'messages'), 'sbLocationsAdmin/new', array(  'class' => 'a-btn big icon a-add sb-locations-new-location',)) ?>  
		<div class="a-ui a-options sb-locations-admin-new-ajax dropshadow">
			<?php include_component('sbLocationsAdmin', 'newLocation') ?>
		</div>
</li>