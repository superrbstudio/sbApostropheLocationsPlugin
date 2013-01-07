<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PluginsbLocationsComponents
 *
 * @author pureroon
 */
abstract class PluginsbLocationsComponents extends sfComponents 
{
  public function executeLocationSlideShow(sfWebRequest $request)
	{
		$defaults = array('class' => 'sb-location-main',
											'dimensions' => sfConfig::get('app_sbLocations_slideshow', array('width' => 600, 'height' => 400)));
		$this->sbLocation = $this->location;
		if(!is_array($this->params)){ $this->params = array(); }
		$this->params     = array_merge($defaults, $this->params);
	}
  
  public function executeDisplayMap(sfWebRequest $request)
  {
    
    if(isset($this->categories))
    {
      $categories = http_build_query(array('categories' => $this->categories));
      $this->markersUrl = url_for('@sb_locations_map_list') . '?' . $categories;
    }
    else
    {
      $this->markersUrl = url_for('@sb_locations_map_list');
    }
  }
}