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
    $this->pagerUrl     = 
    
    $this->pager = new sfDoctrinePager('sbLocation');
    $this->pager->setMaxPerPage($this->max_per_page);
    $this->pager->setPage($this->getRequestParameter('page', 1));
    
    $result = Doctrine_Query::create()->from('sbLocation AS l');
    
    if(!empty($this->categoryIds) and count($this->categoryIds) > 0)
    {
      $result->innerJoin('l.Categories c WITH c.id IN (' . implode(',', $this->categoryIds) . ')');
    }
    
    $result->andWhere('active = 1');
    $result->orderBy('l.title');
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
  }
}