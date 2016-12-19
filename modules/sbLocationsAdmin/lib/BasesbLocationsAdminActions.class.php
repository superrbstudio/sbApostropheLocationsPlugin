<?php

require_once dirname(__FILE__).'/../lib/sbLocationsAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sbLocationsAdminGeneratorHelper.class.php';

/**
 * sbLocationsAdminActions actions.
 * 
 * @package    sbApostropheLocationsPlugin
 * @subpackage sbLocationsAdmin
 * @author     Giles Smith <tech@superrb.com>
 */
class BasesbLocationsAdminActions extends autoSbLocationsAdminActions 
{
	// You must create with at least a title
  public function executeNew(sfWebRequest $request)
  {
    $this->forward404();
  }
	
	// Doctrine collection routes make it a pain to change the settings
  // of the standard routes fundamentally, so we provide another route
  public function executeNewWithTitle(sfWebRequest $request)
  {
		sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
    $this->form = new sbLocationsNewLocationForm();
    $this->form->bind($request->getParameter('sb_locations_new_location'));
		$this->getResponse()->setHttpHeader('Content-Type','application/json; charset=utf-8');
		
    if ($this->form->isValid())
    {
			$this->location = new sbLocation();
			$this->location->setTitle($this->form->getValue('title'));
			$this->location->Author = $this->getUser()->getGuardUser();
			$this->location->save();
			$this->getResponse()->setContent(json_encode(array('status' => true, 'redirect_url' => url_for('@sb_location_admin') . "/" . $this->location->getId() . "/edit")));
    }
		else
		{
			$this->getResponse()->setContent(json_encode(array('status' => false, 'error' => array($this->form->renderGlobalErrors()))));
		}
    
		return sfView::NONE;
  }
  
  public function executeEdit(sfWebRequest $request)
  {
  	$this->mapSystem = sfConfig::get('app_sbLocations_map_system', 'sbGoogleMap');
  	
  	switch($this->mapSystem)
  	{
	  	case 'sbGoogleMap':
//	  		$this->getResponse()->addJavascript('https://maps.google.com/maps/api/js?sensor=false');
	  		break;
	  		
	  	case 'sbOpenStreetMap':
	  	  $this->getResponse()->addJavascript('http://www.openlayers.org/api/OpenLayers.js');
	  	  break;
  	}
  	
    return parent::executeEdit($request);
  }
  
  public function executeRedirect()
  {
    $sbLocation = $this->getRoute()->getObject();
    aRouteTools::pushTargetEnginePage($sbLocation->findBestEngine());
    $this->redirect($this->generateUrl('sb_location', $this->getRoute()->getObject()));
  }
}
