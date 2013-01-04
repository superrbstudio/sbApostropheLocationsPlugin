<?php

/**
 * Description of PluginsbSingleLocationMapSlotComponents
 *
 * @author giles
 */
class PluginsbSingleLocationMapSlotComponents extends aSlotComponents 
{
  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
    
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new sbSingleLocationMapSlotEditForm($this->id, $this->slot->getArrayValue());
    }
  }
  public function executeNormalView()
  {
    $this->setup();
    $this->values = $this->slot->getArrayValue();
    $this->mapSystem = sfConfig::get('app_sbLocations_map_system', 'sbGoogleMaps');
    $this->latitude = '';
    $this->longitude = '';
    $this->description = '';
    $this->title = '';
    
    /*switch($this->mapSystem)
  	{
	  	case 'sbGoogleMap':
	  		$this->getResponse()->addJavascript('https://maps.google.com/maps/api/js?sensor=false');
	  		break;
	  		
	  	case 'sbOpenStreetMap':
	  	  $this->getResponse()->addJavascript('http://www.openlayers.org/api/OpenLayers.js');
	  	  break;
  	}*/
    
    if(isset($this->values['latitude']) and is_numeric($this->values['latitude']))
    {
      $this->latitude = $this->values['latitude'];
    }
    
    if(isset($this->values['longitude']) and is_numeric($this->values['longitude']))
    {
      $this->longitude = $this->values['longitude'];
    }
    
    if(isset($this->values['description']) and !empty($this->values['description']))
    {
      $this->description = $this->values['description'];
    }
    
    if(isset($this->values['title']) and !empty($this->values['title']))
    {
      $this->title = $this->values['title'];
    }
  }
}