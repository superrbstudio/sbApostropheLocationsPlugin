<?php

/**
 * Description of PluginsbLocationsActions
 *
 * @author pureroon
 */
abstract class PluginsbLocationsActions extends aEngineActions 
{
  public function executeIndex(sfWebRequest $request) 
  {
    // find all of the locations
    $this->locations = sbLocationTable::listLocations();
  }
  
  public function executeLocation(sfWebRequest $request)
  {
    
  }
}