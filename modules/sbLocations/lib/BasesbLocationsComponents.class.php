<?php

/**
 * BasesbLocationsComponents components.
 * 
 * @package    sbApostropheLocationsPlugin
 * @subpackage sbLocations
 * @author     Giles Smith <tech@superrb.com>
 */
abstract class BasesbLocationsComponents extends sfComponents
{
	public function executeLocationSlideShow(sfWebRequest $request)
	{
		$defaults = array('class' => 'sb-location-main',
											'dimensions' => sfConfig::get('app_sbLocations_slideshow', array('width' => 600, 'height' => 400)));
		$this->sbLocation = $this->location;
		$this->params     = array_merge($defaults, $request->getParameter('params', array()));
	}
}