<?php

abstract class BasesbLocationsLookupActions extends BaseaActions
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
}