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
    $this->location = sbLocationTable::getInstance()->findOneBySlug($request->getParameter('slug'));
    $this->forward404Unless($this->location instanceof sbLocation);
    $this->forward404Unless($this->location->getActive() == true);
  }
}