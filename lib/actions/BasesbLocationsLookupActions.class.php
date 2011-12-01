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
				$success = $this->lookupAddress($request->getParameter('address'), sfConfig::get('app_sbLocations_google_geocode_sensor_usage', false));
				if(is_array($success)){ $data = $success; $success = true; }
				break;
			
			default:
				$success = false;
				$data = array();
				break;
		}
		
		if($success)
		{
			$data = json_encode(array('results' => $data, 'status' => 'OK'));
		}
		else
		{
			$data = json_encode(array('results' => array(), 'status' => 'REQUEST_DENIED'));
		}
		
		$this->getResponse()->setContent($data);
		return sfView::NONE;
	}
	
	protected function lookupAddress($address, $sensor = false)
	{
		if($sensor == true) { $sensor = 'true'; } else { $sensor = 'false'; }
		$params = http_build_query(array('address' => $address, 'sensor' => $sensor));
		$result = json_decode(file_get_contents(sfConfig::get('app_sbLocations_google_geocode_lookup_url') . '?' . $params));

		if($result and $result->status == 'OK')
		{
			return $result->results;
		}
		
		return false;
	}
}