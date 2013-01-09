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
    $this->unit = sbLocationTable::getUnit();
    
    $result = Doctrine_Query::create()->from('sbLocation AS l');
    $result->select('l.*');
    
    $categoryIds  = $request->getParameter('categories', null);
    $sbLocationId = $request->getParameter('id', null);
    $proximity    = $request->getParameter('proximity', null);
    
    if(is_array($categoryIds) and count($categoryIds) > 0 and !$sbLocationId)
    {
      $result->innerJoin('l.Categories c WITH c.id IN (' . implode(',', $categoryIds) . ')');
    }
    else if(is_numeric($sbLocationId))
    {
      $result->andWhere('l.id = ?', $sbLocationId);
    }
    
    if(!is_null($proximity))
    {
      $this->proximitySearchForm = new sbLocationProximitySearchForm();
      $this->proximitySearchForm->bind($proximity);
      
      if($this->proximitySearchForm->isValid())
      {
        $lookupAddress = new sbLookupAddress(array('address' => $this->proximitySearchForm->getValue('search') . ',GB'));
        
        if($lookupAddress->lookupGeolocationFromAddress())
        {
          if($this->unit->abbr == 'miles')
          {
            $result->addSelect("(((acos(sin((".$lookupAddress->getLatitude()."*pi()/180)) * sin((l.geocode_latitude*pi()/180))+cos((".$lookupAddress->getLatitude()."*pi()/180)) * cos((l.geocode_latitude*pi()/180)) * cos(((".$lookupAddress->getLongitude()."- l.geocode_longitude)*pi()/180))))*180/pi())*60*1.1515) as distance");
          }
          else
          {
            $result->addSelect("((((acos(sin((".$lookupAddress->getLatitude()."*pi()/180)) * sin((l.geocode_latitude*pi()/180))+cos((".$lookupAddress->getLatitude()."*pi()/180)) * cos((l.geocode_latitude*pi()/180)) * cos(((".$lookupAddress->getLongitude()."- l.geocode_longitude)*pi()/180))))*180/pi())*60*1.1515)*1.609344) as distance");
          }
          
          $result->having('distance <= ?', $this->proximitySearchForm->getValue('distance'));
        } 
      }
    }
    
    $locations = $result->execute(array(), Doctrine::HYDRATE_ARRAY);
    
    $icons = sfConfig::get('app_sbLocations_maps_icons');
    
    foreach($locations as $location)
    {
      $da = array('id' => $location['id'],
                  'name' => $location['address_line1'], 
                  'description' => html_entity_decode($this->getPartial('sbLocations/mapDescription', array('sbLocation' => $location, 'engineSlug' => $request->getParameter('engine_slug', null)))),
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