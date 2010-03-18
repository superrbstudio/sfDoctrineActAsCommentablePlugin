<?php

/**
 * PluginComment form.
 *
 * @package    sfDoctrineActAsCommentablePlugin
 * @subpackage Filters
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginCommentFormFilter extends BaseCommentFormFilter
{
  
  public function setup()
  {
    parent::setup();
    $this->useFields(array(
      'commentable_model'
      ));
    $choices =  array_merge(array("All" => "All"), Doctrine::getTable('Comment')->getModels());
    $this->setWidget('commentable_model', new sfWidgetFormChoice(
      array("choices" => $choices)
    ));
    $this->setValidator('commentable_model', new sfValidatorChoice(array("choices" => $choices, "required" => false)));
  }
  
  public function convertCommentableModelValue($value)
  {
    return $value != 'All' ? $value : false;
  }
  
  public function getFields()
  {
    $fields = parent::getFields();
    $fields['commentable_model'] = 'ForeignKey';
    return $fields;
  }
  
}
