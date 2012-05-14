<?php

abstract class PluginsbLocationsLookupActions extends BaseaActions
{
	public function executeLookup(sfWebRequest $request)
	{
		$this->forward404Unless($this->getUser()->isAuthenticated());
		$this->getResponse()->setHttpHeader('Content-Type','application/json; charset=utf-8');
		
		switch($request->getParameter('lookup'))
		{
			case 'address':
				$lookup = new sbLookupAddress(array('address' => $request->getParameter('address'),
																						'api_url' => sfConfig::get('app_sbLocations_google_geocode_lookup_url')));
				
				$success = 'REQUEST_DENIED';
				$data    = array();
				
				if($lookup->lookupGeolocationFromAddress())
				{
					$success = 'OK';
					$data = array('address' => $lookup->getAddress(),
												'latitude' => $lookup->getLatitude(),
												'longitude' => $lookup->getLongitude(),
												'real_address' => $lookup->getRealAddress(),
												'formatted_address' => $lookup->getFormattedAddress(),
												'from_cache' => $lookup->getDataPulledFromCache());
				}
				break;
			
			default:
				$success = 'REQUEST_DENIED';
				$data = array();
				break;
		}
		
		$this->getResponse()->setContent(json_encode(array('results' => $data, 'status' => $success)));
		return sfView::NONE;
	}
  
  public function executeMapList(sfWebRequest $request)
  {
    $data = array();
    $this->getResponse()->setHttpHeader('Content-Type','application/json; charset=utf-8');
    
    $locations = sbLocationTable::getInstance()->findByActive(true);
    
    foreach($locations as $location)
    {
      $da = array('id' => $location['id'],
                  'name' => $location['address_line1'], 
                  'description' => $this->getPartial('sbLocations/mapDescription', array('location' => $location)),
                  'lat' => $location['geocode_latitude'], 
                  'lng' => $location['geocode_longitude'],
                  'icon' => sfConfig::get('app_sbLocations_icon'), 
                  'shadow' => sfConfig::get('app_sbLocations_shadow'));
      
      $data[] = $da;
    }
    
    $this->getResponse()->setContent(json_encode($data));
		return sfView::NONE;
  }
}