<?php

/**
 * Description of PluginsbLocationsActions
 *
 * @author pureroon
 */
abstract class PluginsbLocationsActions extends aEngineActions 
{
  public function executeIndex(sfWebRequest $request) 
  {
    $this->categoryIds  = aArray::getIds($this->page->Categories);
    $this->max_per_page = sfConfig::get('app_sbLocations_max_per_page', 20);
    $this->unit         = sbLocationTable::getUnit();
    
    $this->pager = new sfDoctrinePager('sbLocation');
    $this->pager->setMaxPerPage($this->max_per_page);
    $this->currentPage = $this->getRequestParameter('page', 1);
    $this->pager->setPage($this->currentPage);
    
    $result = Doctrine_Query::create()->from('sbLocation AS l');
    $result->select('l.*');
    
    if(!empty($this->categoryIds) and count($this->categoryIds) > 0)
    {
      $result->addSelect('c.*');
      $result->innerJoin('l.Categories c WITH c.id IN (' . implode(',', $this->categoryIds) . ')');
    }
    
    // do we do proximity
    $this->proximitySearchTerm = $request->getParameter('proximity', null);
    $this->proximitySearchForm = new sbLocationProximitySearchForm();
    $orderSet = false;
    
    if(!is_null($this->proximitySearchTerm))
    {
      $this->proximitySearchForm->bind($this->proximitySearchTerm);
      
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
          $result->orderBy('distance');
          $orderSet = true;
        } 
      }
    }
    
    $result->andWhere('active = 1');
    if(!$orderSet) { $result->orderBy('l.title'); }
    $this->pager->setQuery($result);
    $this->pager->init();
  }
  
  public function executeLocation(sfWebRequest $request)
  {
    $this->sbLocation = sbLocationTable::getInstance()->findOneBySlug($request->getParameter('slug'));
    $this->forward404Unless($this->sbLocation instanceof sbLocation);
    $this->forward404Unless($this->sbLocation->getActive() == true);
    
    $prefix = aTools::getOptionI18n('title_prefix');
    $suffix = aTools::getOptionI18n('title_suffix');
    $this->getResponse()->setTitle($prefix . $this->sbLocation->getTitle() . $suffix, false);
    $this->getResponse()->addMeta('description', strip_tags($this->sbLocation->getDescription()));
  }
}