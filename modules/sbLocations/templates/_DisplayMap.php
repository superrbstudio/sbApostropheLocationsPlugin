<div id="sb-locations-map-container"></div>
<?php if(isset($mapTitle)): ?>
<h2 class="sb-location-map-title"><?php echo html_entity_decode($mapTitle); ?></h2>
<?php endif; ?>
<?php a_js_call('sbLocationsLoadMap(?)', html_entity_decode($markersUrl)); ?>
