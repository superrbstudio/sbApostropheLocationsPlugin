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
    
    switch($this->mapSystem)
  	{
	  	case 'sbGoogleMap':
	  		$this->getResponse()->addJavascript('https://maps.google.com/maps/api/js?sensor=false');
	  		break;
	  		
	  	case 'sbOpenStreetMap':
	  	  $this->getResponse()->addJavascript('http://www.openlayers.org/api/OpenLayers.js');
	  	  break;
  	}
  }
}