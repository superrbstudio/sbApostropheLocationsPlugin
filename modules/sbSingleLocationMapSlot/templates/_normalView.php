<?php use_helper('a') ?>

<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>

<?php if(isset($values['latitude']) and is_numeric($values['latitude']) and isset($values['longitude']) and is_numeric(($values['longitude']))): ?>
<div id="sb-single-location-map-<?php echo $permid; ?>" class="sb-single-location-map-slot-map"></div>
<?php a_js_call('sbSingleLocationMapSlot(?)', array('mapSystem' => sfConfig::get('app_sbLocations_map_system', 'sbGoogleMap'), 'latitude' => $values['latitude'], 'longitude' => $values['longitude'], 'divId' => 'sb-single-location-map-' . $permid)); ?>
<?php else: ?>
<p>Unable to find map location. Please check your address and try again.</p>
<?php endif; ?>


