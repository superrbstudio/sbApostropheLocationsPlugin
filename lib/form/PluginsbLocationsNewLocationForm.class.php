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
    $this->widgetSchema->setNameFormat('sb_locations_new_location[%s]');
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}
