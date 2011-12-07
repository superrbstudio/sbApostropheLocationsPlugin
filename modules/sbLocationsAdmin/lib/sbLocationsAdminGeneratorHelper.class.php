<?php

/**
 * sbLocationsAdmin module helper.
 *
 * @package    pmpmhotels
 * @subpackage sbLocationsAdmin
 * @author     Your name here
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sbLocationsAdminGeneratorHelper extends BaseSbLocationsAdminGeneratorHelper
{
  public function linkToSave($object, $params)
  {
    return '<li class="a-admin-action-save">' . a_anchor_submit_button(a_('Save', array(), 'apostrophe'), array('a-save'), '_save') . '</li>';
  }
}
