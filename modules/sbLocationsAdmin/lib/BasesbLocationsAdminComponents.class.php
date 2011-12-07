<?php

/**
 * BasesbLocationsAdminComponents components.
 * 
 * @package    sbApostropheLocationsPlugin
 * @subpackage sbLocationsAdmin
 * @author     Giles Smith <tech@superrb.com>
 */
abstract class BasesbLocationsAdminComponents extends sfComponents
{
	public function executeNewLocation()
  {
    $this->form = new sbLocationsNewLocationForm();
  }
	
	public function executePhotos()
	{
		
	}
}
