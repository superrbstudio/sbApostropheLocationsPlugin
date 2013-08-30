<?php

/**
 * Description of PluginsbLocationProximitySearchForm
 *
 * @author giles
 */
class PluginsbLocationProximitySearchForm extends sfForm 
{
  protected $distanceChoices;
  
  public function setup()
  {
    parent::setup();
    
    $this->disableCSRFProtection();
    
    $this->setWidget('search', new sfWidgetFormInputText(array('label' => 'Your Location (Postcode)')));
    $this->setValidator('search', new sfValidatorString(array('required' => true), array('required' => 'Please enter a Search Location')));
    
    
    $this->setWidget('distance', new sfWidgetFormChoice(array('choices' => $this->getDistanceChoices())));
    $this->setValidator('distance', new sfValidatorChoice(array('choices' => array_keys($this->getDistanceChoices()))));
    
    $this->widgetSchema->setNameFormat('proximity[%s]');
  }
  
  protected function getDistanceChoices()
  {
    if(!isset($this->distanceChoices))
    {
      $unit = sbLocationTable::getUnit();
      $this->distanceChoices = array(
          '0.5' => '0.5 ' . $unit->abbr, 
          '1' => '1 ' . $unit->abbr, 
          '3' => '3 ' . $unit->abbr, 
          '5' => '5 ' . $unit->abbr,
          '10' => '10 ' . $unit->abbr,
		  '15' => '15 ' . $unit->abbr,
		  '20' => '20 ' . $unit->abbr,
		  '50' => '50 ' . $unit->abbr
      );
    }
    
    return $this->distanceChoices;
  }
}