<?php

/**
 * PluginsbLocation
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginsbLocation extends BasesbLocation
{
	public function getSearchText()
	{
		return $this->getTitle() . " " . 
						$this->getDescription() . " " . 
						$this->getMapDescription() . " " . 
						$this->getAddress('comma') . " " . 
						$this->getTelephoneLandline() . " " . 
						$this->getTelephoneMobile() . " " . 
						implode(' ', $this->getTags());
	}

	public function getSummary()
	{
		return $this->getTitle();
	}

	public function postSave($event)
	{
		parent::postSave($event);

		aTools::$searchService->update(
			array(
				'item' => $this,
				'text' => $this->getSearchText(),
				'info' => array('summary' => $this->getSummary()),
				'culture' => aTools::getUserCulture()));
	}

	public function postDelete($event)
	{
		parent::postDelete($event);

		aTools::$searchService->delete(
			array(
				'item' => $this
			)
		);
	}
	
	/**
	 * Add in a co-ordinate lookup before saving
	 */
	public function preSave($obj)
	{	
		// set the geo co-ordinates if they haven't been set
		$testLat = $this->getGeocodeLatitude();
		$testLon = $this->getGeocodeLongitude();
		
		if(empty($testLon) or !is_numeric($testLon) or empty($testLat) or !is_numeric($testLat))
		{
			$lookup = new sbLookupAddress(array('address' => $this->getAddress('comma'),
																					'api_url' => sfConfig::get('app_sbLocations_google_geocode_lookup_url')));
			
			if($lookup->lookupGeolocationFromAddress())
			{
				$this->setGeocodeLongitude($lookup->getLongitude());
				$this->setGeocodeLatitude($lookup->getLatitude());
			}
		}
		
		parent::preSave($obj);
	}
	
	/** 
	 * Formats the address
	 * @param string $format [comma, newline, array]
	 * @return mixed
	 */
	public function getAddress($format = 'array')
	{
		$address = array();
		
		if($this->getAddressLine1() != ''){ $address['line1'] = $this->getAddressLine1(); }
		if($this->getAddressLine2() != ''){ $address['line2'] = $this->getAddressLine2(); }
		if($this->getAddressTownCity() != ''){ $address['town_city'] = $this->getAddressTownCity(); }
		if($this->getAddressCounty() != ''){ $address['county'] = $this->getAddressCounty(); }
		if($this->getAddressState() != ''){ $address['state'] = $this->getAddressState(); }
		if($this->getAddressPostalCode() != ''){ $address['postal_code'] = $this->getAddressPostalCode(); }
		if($this->getAddressCountry() != ''){ $address['country'] = $this->getAddressCountry(); }
		
		switch($format)
		{
			case 'comma':
				$address = implode(', ', $address);
				break;
			
			case 'newline':
				$address = implode('\r\n', $address);
				break;
		}
		
		return $address;
	}
	
	public function getSlideShowName()
	{
		return sbLocationTable::getSlideShowName($this);
	}
	
	public function getSlideShowSlug()
	{
		return sbLocationTable::getSlideShowSlug($this);
	}
	
	public function getModelName()
	{
		return get_class($this);
	}
	
	public function getFirstImage()
	{
		return sbLocationTable::getFirstImage($this);
	}
}