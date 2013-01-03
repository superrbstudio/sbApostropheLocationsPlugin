<?php

/**
 * Description of PluginsbSingleLocationMapSlotEditForm
 *
 * @author giles
 */
class PluginsbSingleLocationMapSlotEditForm extends BaseForm 
{
  // Ensures unique IDs throughout the page
  protected $id;
  public function __construct($id, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->id = $id;
    parent::__construct($defaults, $options, $CSRFSecret);
  }
  public function configure()
  {
    // ADD YOUR FIELDS HERE
    $this->setWidget('address_line1', new sfWidgetFormInputText());
    $this->setValidator('address_line1', new sfValidatorString(array('required' => false)));
    
    $this->setWidget('address_line2', new sfWidgetFormInputText());
    $this->setValidator('address_line2', new sfValidatorString(array('required' => false)));
    
    $this->setWidget('address_town_city', new sfWidgetFormInputText());
    $this->setValidator('address_town_city', new sfValidatorString(array('required' => false)));
    
    $this->setWidget('address_county', new sfWidgetFormInputText());
    $this->setValidator('address_county', new sfValidatorString(array('required' => false)));
    
    $this->setWidget('address_state', new sfWidgetFormInputText());
    $this->setValidator('address_state', new sfValidatorString(array('required' => false)));
    
    $this->setWidget('address_country', new sfWidgetFormI18nChoiceCountry());
    $this->setValidator('address_country', new sfValidatorI18nChoiceCountry(array('required' => false)));
    $this->setDefault('address_country', 'GB');
    
    $this->setWidget('address_postal_code', new sfWidgetFormInputText());
    $this->setValidator('address_postal_code', new sfValidatorString(array('required' => false)));
    
    $this->setWidget('latitude', new sfWidgetFormInputText(array(), array('readonly' => 'readonly')));
    $this->setValidator('latitude', new sfValidatorString(array('required' => false)));
    
    $this->setWidget('longitude', new sfWidgetFormInputText(array(), array('readonly' => 'readonly')));
    $this->setValidator('longitude', new sfValidatorString(array('required' => false)));
    
    $this->widgetSchema->setHelp('longitude', 'Latitude and Longitude will be calculated automatically for you');
    
    // Ensures unique IDs throughout the page. Hyphen between slot and form to please our CSS
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}