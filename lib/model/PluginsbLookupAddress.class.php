<?php 

class PluginsbLookupAddress
{
	protected $address; // The address that will be used for queries and cache keys
	protected $latitude;
	protected $longitude;
	protected $apiUrl;
	protected $useSensors = false; // Whether or not to use browser locations - probably not as this is server side!
	protected $realAddress; // This is the real address returned from the API
	protected $formattedAddress; // This is the formatted address as returned from the API
	protected $cacheTime = '3 seconds';
	protected $cache; // holder for the cache
	protected $dataPulledFromCache = false;
  protected $mapSystem; // the Mapping system to perform the lookup on
	
	static $cacheName = 'sb_lookup_address';


	/**
	 * Constructs the object
	 * @param array $params prepopulate the object
	 *  - address - The address to perform lookups on
	 *  - latitude - A preset latitude
	 *  - longitude - A preset longitude
	 *  - use_sensors - Whether or not to use location sensors, normally no.
	 */
	public function __construct($params = array()) 
	{
		if(isset($params['address'])) { $this->setAddress($params['address']); }
		if(isset($params['latitude'])) { $this->setLatitude($params['latitude']); }
		if(isset($params['longitude'])) { $this->setLongitude($params['longitude']); }
		if(isset($params['use_sensors'])) { $this->setUseSensors($params['use_sensors']); }
    
    $this->mapSystem = sfConfig::get('app_sbLocations_map_system', 'sbGoogleMap');
    
    switch($this->mapSystem)
    {
      case 'sbOpenStreetMap':
        $this->setApiUrl('http://nominatim.openstreetmap.org/search');
        break;
      
      default:
        $this->setApiUrl('https://maps.googleapis.com/maps/api/geocode/json');
    }
    
		$this->cache = aCacheTools::get(self::$cacheName);
	}
	
	public function lookupGeolocationFromAddress()
	{
		if($this->getAddress() == '') { return false; }
		if($this->getLookupFromCache()){ return true; }
    
    if($this->mapSystem == 'sbGoogleMap')
    {
      $params = http_build_query(array('address' => $this->getAddress(), 'sensor' => $this->getUseSensorsAsString()));
      $result = json_decode(file_get_contents($this->getApiUrl() . '?' . $params));
      $success = false;

      if($result and $result->status == 'OK')
      {
        if(isset($result->results[0])) // default to first result
        {
          $address = $result->results[0];

          if(isset($address->formatted_address)){ $this->setFormattedAddress($address->formatted_address); }
          if(isset($address->address_components)){ $this->setRealAddress($address->address_components); }

          if(isset($address->geometry->location))
          { 
            $this->setLatitude(floatval($address->geometry->location->lat));
            $this->setLongitude(floatval($address->geometry->location->lng));
          }
        }

        $this->setLocationToCache();
        $success = true;
      }
		
      return $success;
    }
    
    if($this->mapSystem == 'sbOpenStreetMap')
    {
      $params = http_build_query(array('q' => $this->getAddress(), 'format' => 'json', 'addressdetails' => 1));
      $result = json_decode(file_get_contents($this->getApiUrl() . '?' . $params));
      $success = false;
      
      if($result != null and count($result) > 0)
      {
        $address = $result[0];
        
        if(isset($address->display_name)){ $this->setFormattedAddress($address->display_name); }
        if(isset($address->address)){ $this->setRealAddress($address->address); }
        if(isset($address->lat)){ $this->setLatitude(floatval($address->lat)); }
        if(isset($address->lon)){ $this->setLongitude(floatval($address->lon)); }
        
        $this->setLocationToCache();
        $success = true;
      }

      return $success;
    }
    
    return false;
	}
	
	/**
	 * Sets the current address 
	 * @param string $address
	 * @return boolean
	 */
	public function setAddress($address)
	{
		if(empty($address)){ return false; }
		$this->address = $address;
		return true;
	}
	
	/**
	 * Returns the current address
	 * @return string
	 */
	public function getAddress()
	{
		return $this->address;
	}
	
	/**
	 * Sets the current latitude
	 * @param float $lat
	 * @return boolean 
	 */
	public function setLatitude($lat)
	{
		if(!is_float($lat)){ return false; }
		$this->latitude = $lat;
		return true;
	}
	
	/**
	 * Returns the current latitude
	 * @return float
	 */
	public function getLatitude()
	{
		return $this->latitude;
	}
	
	/** 
	 * Sets the current Longitude
	 * @param float $lon
	 * @return boolean
	 */
	public function setLongitude($lon)
	{
		if(!is_float($lon)) { return false; }
		$this->longitude = $lon;
		return true;
	}
	
	/**
	 * Returns the current longitude
	 * @return float
	 */
	public function getLongitude()
	{
		return $this->longitude;
	}
	
	/**
	 * Sets the URL to use for the API
	 * @param string $url
	 * @return boolean 
	 */
	public function setApiUrl($url)
	{
		if(empty($url)){ return false; }
		$this->apiUrl = $url;
		return true;
	}
	
	/**
	 * Returns the current API URL
	 * @return string 
	 */
	public function getApiUrl()
	{
		return $this->apiUrl;
	}
	
	/**
	 * Sets whether to use the sensors feature
	 * @param boolean $use
	 * @return boolean 
	 */
	public function setUseSensors($use)
	{
		if(!is_bool($use)){ return false; }
		$this->useSensors = $use;
		return true;
	}
	
	/**
	 * Returns whether or not to use the sensors
	 * @return boolean
	 */
	public function getUseSensors()
	{
		return $this->useSensors;
	}
	
	/**
	 * Returns the string version of the use sensors property
	 * @return string
	 */
	public function getUseSensorsAsString()
	{
		if($this->useSensors == true) { return 'true'; }
		if($this->useSensors == false){ return 'false'; }
		return 'false';
	}
	
	/**
	 * Sets the real address returned from the API
	 * @param array $address
	 * @return boolean 
	 */
	public function setRealAddress($address)
	{
		if(!is_array($address)) { return false; }
		$this->realAddress = $address;
		return true;
	}
	
	/** 
	 * Gets the real address as returned by the API
	 * @return array
	 */
	public function getRealAddress()
	{
		return $this->realAddress;
	}
	
	/**
	 * Sets the formatted address from the api
	 * @param string $address
	 * @return boolean 
	 */
	public function setFormattedAddress($address)
	{
		if(empty($address)) { return false; }
		$this->formattedAddress = $address;
		return true;
	}
	
	/**
	 * Returns the Formatted address from the API
	 * @return string 
	 */
	public function getFormattedAddress()
	{
		return $this->formattedAddress;
	}
	
	public function getCacheTime()
	{
		$time = strtotime($this->cacheTime);
		if($time == false){ $time = strotime('+1 month'); }
		return $time;
	}
	
	protected function setDataPulledFromCache($bool)
	{
		if(is_bool($bool)) { $this->dataPulledFromCache = $bool; return true; }
		return false;
	}
	
	public function getDataPulledFromCache()
	{
		return $this->dataPulledFromCache;
	}
	
	protected function getLookupFromCache()
	{
		$data = $this->cache->get($this->getCacheKey());

		if(!is_null($data))
		{
			$data = unserialize($data);
			
			if(is_array($data))
			{
				if(isset($data['formatted_address'])) { $this->setFormattedAddress($data['formatted_address']);}
				if(isset($data['real_address'])) { $this->setRealAddress($data['real_address']);}
				if(isset($data['latitude'])) { $this->setLatitude($data['latitude']);}
				if(isset($data['longitude'])) { $this->setLongitude($data['longitude']);}
				$this->setDataPulledFromCache(true);
				return true;
			}
		}
		
		$this->setDataPulledFromCache(false);
		return false;
	}
	
	protected function setLocationToCache()
	{	
		$data = array('formatted_address' => $this->getFormattedAddress(),
									'real_address' => $this->getRealAddress(),
									'latitude' => $this->getLatitude(),
									'longitude' => $this->getLongitude());
		
		return $this->cache->set($this->getCacheKey(), serialize($data), $this->getCacheTime());
	}
	
	protected function getCacheKey()
	{
		return sha1($this->getAddress());
	}
}