<?php

/**
 * PluginsbLocationTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginsbLocationTable extends Doctrine_Table
{
	/**
		* Returns an instance of this class.
		*
		* @return object PluginsbLocationTable
		*/
	public static function getInstance()
	{
			return Doctrine_Core::getTable('PluginsbLocation');
	}

	public static function getSlideShowName($location)
	{
		return  'sbLocation-' . $location['id'];
	}

	public static function getSlideShowSlug($location)
	{
		return 'sbLocation-' . $location['id'];
	}

	public static function getFirstImage($location)
	{
		$page = aPageTable::retrieveBySlugWithSlots(self::getSlideShowSlug($location));
    
    if($page)
    {
      $slot = $page->getSlot(self::getSlideShowName($location));

      if($slot)
      {
        $images = $slot->getOrderedMediaItems();

        if($images)
        {
          return $images[0]->getCropOriginal();
        }
      }
    }
			
		return false;
	}
  
  public static function listLocations($params = array())
  {
    $result = Doctrine_Query::create()->from('sbLocation AS l')->leftJoin('l.sbVacancy AS v');
    
    if(isset($params['order_by']))
    {
      $result->orderBy($params['order_by']);
    }
    else
    {
      $result->orderBy('l.updated_at DESC');
    }
    
    $fast = sfConfig::get('app_a_fasthydrate', false);
    return $result->execute();
  }
}