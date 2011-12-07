<?php
$dimensions = $params['dimensions'];
a_slot($sbLocation->getSlideShowName(), 'aSlideshow', array("global" => true, 
																													"slug" => $sbLocation->getSlideShowSlug(),
																													"width" => $dimensions['width'], 
																													'height' => $dimensions['height'], 
																													'resizeType' => 'c', 
																													'flexHeight' => true, 
																													'class' => 'blog-post', 
																													'constraints' => array($dimensions['width'], 'minimum-height' => $dimensions['height'])));
?>