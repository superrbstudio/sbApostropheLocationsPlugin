<?php

abstract class PluginsbLocationsLookupActions extends BaseaActions
{
  /**
   * I think this method isn't used any more
   * It also doesn't support Open Street Maps
   * 
   * @param sfWebRequest $request
   * @return sfView::NONE
   */
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
    
    $result = Doctrine_Query::create()->from('sbLocation AS l');
    
    $categoryIds = $request->getParameter('categories', null);
    
    if(is_array($categoryIds) and count($categoryIds) > 0)
    {
      $result->innerJoin('l.Categories c WITH c.id IN (' . implode(',', $categoryIds) . ')');
    }
    
    $locations = $result->execute(array(), Doctrine::HYDRATE_ARRAY);
    
    $icons = sfConfig::get('app_sbLocations_maps_icons');
    
    foreach($locations as $location)
    {
      $da = array('id' => $location['id'],
                  'name' => $location['address_line1'], 
                  'description' => html_entity_decode($this->getPartial('sbLocations/mapDescription', array('location' => $location))),
                  'lat' => $location['geocode_latitude'], 
                  'lng' => $location['geocode_longitude'],
                  'icon' => $icons['icon'], 
                  'shadow' => $icons['shadow']);
      
      $data[] = $da;
    }
    
    $this->getResponse()->setContent(json_encode($data));
		return sfView::NONE;
  }
}