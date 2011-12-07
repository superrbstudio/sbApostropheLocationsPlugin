<?php

require_once dirname(__FILE__).'/../lib/sbLocationsAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sbLocationsAdminGeneratorHelper.class.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BasesbLocationsAdminActions
 *
 * @author pureroon
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
			$this->getResponse()->setContent(json_encode(array('status' => false, 'error' => array('Failed Validation'))));
		}
    
		return sfView::NONE;
  }
}

?>
