<?php

/**
 * PluginsbLocation form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginsbLocationForm extends BasesbLocationForm
{
	public function setup()
	{
		parent::setup();
		$user = sfContext::getInstance()->getUser();
		sfContext::getInstance()->getConfiguration()->loadHelpers('Url');
		
		$widgetOptions['tool'] = 'Sidebar';
		
		$this->setWidget('title', new sfWidgetFormInputText(array(), array('class' => 'large')));
		$this->setValidator('title', new sfValidatorString(array('required' => true), array('required' => 'Please enter a title for the location')));
		
		$this->setWidget('description', new aWidgetFormRichTextarea(array('tool' => 'sbLocation', 'height' => 182), array()));
		$this->setValidator('description', new sfValidatorHtml(array('required' => false), array()));
		$this->setWidget('map_description', new aWidgetFormRichTextarea(array('tool' => 'sbLocation', 'height' => 182), array()));
		$this->setValidator('map_description', new sfValidatorHtml(array('required' => false), array()));
		
		$this->setWidget('website_url', new sfWidgetFormInputText(array(), array('class' => 'large')));
		$this->setValidator('website_url', new sfValidatorUrl(array('required' => false)));
		
		$this->setWidget('address_line1', new sfWidgetFormInputText(array(), array('class' => 'medium')));
		$this->setValidator('address_line1', new sfValidatorString(array('required' => false)));
		
		$this->setWidget('address_line2', new sfWidgetFormInputText(array(), array('class' => 'medium')));
		$this->setValidator('address_line2', new sfValidatorString(array('required' => false)));
		
		$this->setWidget('address_town_city', new sfWidgetFormInputText(array(), array('class' => 'medium')));
		$this->setValidator('address_town_city', new sfValidatorString(array('required' => false)));
		
		$this->setWidget('address_county', new sfWidgetFormInputText(array(), array('class' => 'medium')));
		$this->setValidator('address_county', new sfValidatorString(array('required' => false)));
		
		$this->setWidget('address_state', new sfWidgetFormInputText(array(), array('class' => 'medium')));
		$this->setValidator('address_state', new sfValidatorString(array('required' => false)));
		
		$this->setWidget('address_country', new sfWidgetFormI18nChoiceCountry(array('default' => 'GB'), array('class' => 'medium')));
		$this->setValidator('address_country', new sfValidatorI18nChoiceCountry(array('required' => false)));
		
		$this->setWidget('address_postal_code', new sfWidgetFormInputText(array(), array('class' => 'small')));
		$this->setValidator('address_postal_code', new sfValidatorString(array('required' => false)));
		
		// Geocode - dont need to validate it will be automatically populated if anything is wrong
		$this->setWidget('geocode_latitude', new sfWidgetFormInputHidden(array('label' => 'Lat:'), array()));
		$this->setWidget('geocode_longitude', new sfWidgetFormInputHidden(array('label' => 'Lon:'), array()));
		
		// Tags
		$options['default'] = implode(', ', $this->getObject()->getTags());
		if (sfConfig::get('app_a_all_tags', true))
		{
			$options['all-tags'] = PluginTagTable::getAllTagNameWithCount();
		}
		else
		{
			$options['typeahead-url'] = url_for('taggableComplete/complete');
		}
		
		$options['popular-tags'] = PluginTagTable::getPopulars(null, array(), false);
		$this->setWidget('tags', new pkWidgetFormJQueryTaggable($options, array('class' => 'tags-input')));
		$this->setValidator('tags', new sfValidatorString(array('required' => false)));
		
		// post update validations
		$this->validatorSchema->setPostValidator(
			new sfValidatorAnd(array(
				new sfValidatorDoctrineUnique(array('model' => $this->getModelName(), 'column' => 'title'))
			))
		);
		
		unset($this['created_at'], $this['updated_at']);
	}
	
	public function preUpdate($obj)
	{
		parent::preUpdate($obj);
		die;
	}
}
