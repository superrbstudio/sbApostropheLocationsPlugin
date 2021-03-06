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
    $httpQuery = array();
    
    if(isset($this->categories))
    {
      $httpQuery['categories'] = $this->categories;
    }
    
    if(isset($this->sbLocation))
    {
      $httpQuery['id'] = $this->sbLocation['id'];
    }
    
    if(isset($this->engineSlug))
    {
      $httpQuery['engine_slug'] = $this->engineSlug;
    }
    
    if(isset($this->proximitySearchForm) and $this->proximitySearchForm->isValid())
    {
      $httpQuery['proximity'] = array('search' => $this->proximitySearchForm->getValue('search'), 'distance' => $this->proximitySearchForm->getValue('distance'));
    }
    
    $this->markersUrl = url_for('@sb_locations_map_list') . '?' . http_build_query($httpQuery);
  }
}