<?php
$dimensions = $params['dimensions'];
a_slot(sbLocationTable::getSlideShowName($sbLocation), 'aSlideshow', array("global" => true, 
																													"slug" => sbLocationTable::getSlideShowSlug($sbLocation),
																													"width" => $dimensions['width'], 
																													'height' => $dimensions['height'], 
																													'resizeType' => 'c', 
																													'flexHeight' => false, 
																													'class' => 'blog-post', 
																													'constraints' => array('minimum-width' => $dimensions['width'], 'minimum-height' => $dimensions['height'], 'aspect-width' => $dimensions['width'], 'aspect-height' => $dimensions['height'])));
?>