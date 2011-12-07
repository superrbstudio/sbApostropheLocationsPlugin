<?php

/**
 * PluginsbLocationsNewLocationForm form.
 *
 * @package    sbApostropheLocationsPlugin
 * @author     Giles Smith <tech@superrb.com>
 */
abstract class PluginsbLocationsNewLocationForm extends BaseForm 
{
	public function configure()
  {
    parent::configure();
    $this->setWidget('title', new sfWidgetFormInputText());
    $this->setValidator('title', new sfValidatorString(array('min_length' => 2, 'required' => true)));
		$this->setWidget('address_country', new sfWidgetFormInputHidden(array('default' => 'GB')));
		$this->setValidator('address_country', new sfValidatorI18nChoiceCountry(array('required' => false), array()));
    $this->widgetSchema->setNameFormat('sb_locations_new_location[%s]');
    $this->widgetSchema->setFormFormatterName('aAdmin');
		
		// post update validations
		$this->validatorSchema->setPostValidator(
			new sfValidatorAnd(array(
				new sfValidatorDoctrineUnique(array('model' => 'sbLocation', 'column' => 'title'))
			))
		);
  }
}
