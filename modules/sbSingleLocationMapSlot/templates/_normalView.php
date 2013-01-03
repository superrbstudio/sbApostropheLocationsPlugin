<?php use_helper('a') ?>

<?php include_partial('a/simpleEditWithVariants', array('pageid' => $pageid, 'name' => $name, 'permid' => $permid, 'slot' => $slot)) ?>

<?php if(is_numeric($latitude) and is_numeric(($longitude))): ?>
<div id="sb-single-location-map-<?php echo $permid; ?>" class="sb-single-location-map-slot-map"></div>
<h2 id="sb-single-location-map-title-<?php echo $permid; ?>" class="sb-single-location-map-slot-title"><?php echo $title; ?></h2>
<?php else: ?>
<p>Unable to find map location. Please check your address and try again.</p>
<?php endif; ?>
<?php a_js_call('sbSingleLocationMapSlot(?)', array('mapSystem' => sfConfig::get('app_sbLocations_map_system', 'sbGoogleMap'), 'latitude' => $latitude, 'longitude' => $longitude, 'divId' => 'sb-single-location-map-' . $permid, 'description' => $description)); ?>


